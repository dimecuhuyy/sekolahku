<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Profile;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['update'],
                'rules' => [
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $profileModel = Profile::findOne(1);
        if ($profileModel === null) {
            $profileModel = new Profile([
                'id' => 1,
                'visi_misi' => 'Mewujudkan peserta didik yang berakhlak mulia, cerdas, dan berprestasi.',
                'sejarah' => 'Sekolah berdiri sejak tahun 1990 dengan komitmen pada pendidikan berkualitas.',
                'nomor_telpon' => '0812-3456-7890',
                'akreditasi' => 'A',
                'profil_singkat' => 'Sekolah kami berfokus pada pengembangan karakter dan akademik.',
            ]);
            $profileModel->save(false);
        }
        $profile = [
            'visiMisi' => $profileModel->visi_misi,
            'sejarah' => $profileModel->sejarah,
            'nomorTelpon' => $profileModel->nomor_telpon,
            'akreditasi' => $profileModel->akreditasi,
            'profilSingkat' => $profileModel->profil_singkat,
        ];
        return $this->render('@app/views/site/profil', [
            'profile' => $profile,
        ]);
    }

    public function actionUpdate()
    {
        $profileModel = Profile::findOne(1);
        if ($profileModel === null) {
            $profileModel = new Profile(['id' => 1]);
        }
        $field = Yii::$app->request->post('field');
        $value = Yii::$app->request->post('value');
        $map = [
            'visiMisi' => 'visi_misi',
            'sejarah' => 'sejarah',
            'nomorTelpon' => 'nomor_telpon',
            'akreditasi' => 'akreditasi',
        ];
        if (isset($map[$field])) {
            $column = $map[$field];
            $profileModel->$column = $value;
            $profileModel->save(false);
            Yii::$app->session->setFlash('success', 'Profil diperbarui.');
        }
        return $this->redirect(['index']);
    }
}
