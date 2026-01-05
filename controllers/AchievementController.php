<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\Achievement;

class AchievementController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $models = Achievement::find()->orderBy(['created_at' => SORT_DESC])->all();
        $items = [];
        foreach ($models as $m) {
            $images = array_values(array_filter([$m->image1, $m->image2, $m->image3]));
            $items[] = [
                'id' => (string)$m->id,
                'title' => $m->title,
                'achiever' => $m->achiever,
                'description' => $m->description,
                'images' => $images,
            ];
        }
        return $this->render('@app/views/site/prestasi', ['items' => $items]);
    }

    public function actionCreate()
    {
        $model = new Achievement();
        $model->title = Yii::$app->request->post('title');
        $model->achiever = Yii::$app->request->post('achiever');
        $model->description = Yii::$app->request->post('description');
        [$model->image1, $model->image2, $model->image3] = $this->handleUploads(['image1', 'image2', 'image3']);
        $model->save(false);
        Yii::$app->session->setFlash('success', 'Prestasi ditambahkan.');
        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        $model = Achievement::findOne($id);
        if ($model) {
            $model->title = Yii::$app->request->post('title');
            $model->achiever = Yii::$app->request->post('achiever');
            $model->description = Yii::$app->request->post('description');
            $uploads = $this->handleUploads(['image1', 'image2', 'image3']);
            if ($uploads[0]) $model->image1 = $uploads[0];
            if ($uploads[1]) $model->image2 = $uploads[1];
            if ($uploads[2]) $model->image3 = $uploads[2];
            $model->save(false);
        }
        Yii::$app->session->setFlash('success', 'Prestasi diperbarui.');
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $model = Achievement::findOne($id);
        if ($model) {
            $model->delete();
        }
        Yii::$app->session->setFlash('success', 'Prestasi dihapus.');
        return $this->redirect(['index']);
    }

    private function handleUploads(array $names): array
    {
        $results = [null, null, null];
        $uploadDir = Yii::getAlias('@app/web/uploads');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        foreach ($names as $idx => $name) {
            $file = UploadedFile::getInstanceByName($name);
            if ($file && $file->tempName) {
                $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($file->name, PATHINFO_FILENAME));
                $ext = strtolower($file->getExtension());
                $filename = $safeBase . '_' . time() . '_' . mt_rand(1000,9999) . '.' . $ext;
                $path = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                if ($file->saveAs($path)) {
                    $results[$idx] = '/uploads/' . $filename;
                }
            }
        }
        return $results;
    }
}
