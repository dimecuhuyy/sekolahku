<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Profile;
use app\models\Achievement;
use app\models\News;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'profil-update', 'prestasi-create', 'prestasi-update', 'prestasi-delete', 'berita-create', 'berita-update', 'berita-delete'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['profil-update', 'prestasi-create', 'prestasi-update', 'prestasi-delete', 'berita-create', 'berita-update', 'berita-delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->isAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'prestasi-delete' => ['post'],
                    'berita-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
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
        $newsModels = News::find()->orderBy(['created_at' => SORT_DESC])->limit(3)->all();
        $profile = [
            'visiMisi' => $profileModel->visi_misi,
            'sejarah' => $profileModel->sejarah,
            'nomorTelpon' => $profileModel->nomor_telpon,
            'akreditasi' => $profileModel->akreditasi,
            'profilSingkat' => $profileModel->profil_singkat,
        ];
        $news = [];
        foreach ($newsModels as $m) {
            $images = array_values(array_filter([$m->image1, $m->image2, $m->image3]));
            $news[] = [
                'id' => (string)$m->id,
                'title' => $m->title,
                'description' => $m->description,
                'images' => $images,
            ];
        }
        return $this->render('index', [
            'profile' => $profile,
            'news' => $news,
        ]);
    }

    public function actionProfil()
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
        return $this->render('profil', [
            'profile' => $profile,
        ]);
    }

    public function actionProfilUpdate()
    {
        if (!$this->isAdmin()) {
            return $this->goHome();
        }
        $field = Yii::$app->request->post('field');
        $value = Yii::$app->request->post('value');
        if ($field && is_string($value)) {
            $profileModel = Profile::findOne(1);
            if ($profileModel === null) {
                $profileModel = new Profile(['id' => 1]);
            }
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
            }
            Yii::$app->session->setFlash('success', 'Profil diperbarui.');
        }
        return $this->redirect(['profil']);
    }

    public function actionPrestasi()
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
        return $this->render('prestasi', ['items' => $items]);
    }

    public function actionPrestasiCreate()
    {
        $title = Yii::$app->request->post('title');
        $achiever = Yii::$app->request->post('achiever');
        $description = Yii::$app->request->post('description');
        if ($title && $achiever) {
            $model = new Achievement();
            $model->title = $title;
            $model->achiever = $achiever;
            $model->description = $description;
            $model->image1 = Yii::$app->request->post('image1');
            $model->image2 = Yii::$app->request->post('image2');
            $model->image3 = Yii::$app->request->post('image3');
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Prestasi ditambahkan.');
        }
        return $this->redirect(['prestasi']);
    }

    public function actionPrestasiUpdate($id)
    {
        $title = Yii::$app->request->post('title');
        $achiever = Yii::$app->request->post('achiever');
        $description = Yii::$app->request->post('description');
        $model = Achievement::findOne($id);
        if ($model) {
            $model->title = $title;
            $model->achiever = $achiever;
            $model->description = $description;
            $model->image1 = Yii::$app->request->post('image1');
            $model->image2 = Yii::$app->request->post('image2');
            $model->image3 = Yii::$app->request->post('image3');
            $model->save(false);
        }
        Yii::$app->session->setFlash('success', 'Prestasi diperbarui.');
        return $this->redirect(['prestasi']);
    }

    public function actionPrestasiDelete($id)
    {
        $model = Achievement::findOne($id);
        if ($model) {
            $model->delete();
        }
        Yii::$app->session->setFlash('success', 'Prestasi dihapus.');
        return $this->redirect(['prestasi']);
    }

    public function actionBerita()
    {
        $models = News::find()->orderBy(['created_at' => SORT_DESC])->all();
        $items = [];
        foreach ($models as $m) {
            $images = array_values(array_filter([$m->image1, $m->image2, $m->image3]));
            $items[] = [
                'id' => (string)$m->id,
                'title' => $m->title,
                'description' => $m->description,
                'images' => $images,
            ];
        }
        return $this->render('berita', ['items' => $items]);
    }

    public function actionBeritaCreate()
    {
        $title = Yii::$app->request->post('title');
        $description = Yii::$app->request->post('description');
        if ($title) {
            $model = new News();
            $model->title = $title;
            $model->description = $description;
            $model->image1 = Yii::$app->request->post('image1');
            $model->image2 = Yii::$app->request->post('image2');
            $model->image3 = Yii::$app->request->post('image3');
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Berita ditambahkan.');
        }
        return $this->redirect(['berita']);
    }

    public function actionBeritaUpdate($id)
    {
        $title = Yii::$app->request->post('title');
        $description = Yii::$app->request->post('description');
        $model = News::findOne($id);
        if ($model) {
            $model->title = $title;
            $model->description = $description;
            $model->image1 = Yii::$app->request->post('image1');
            $model->image2 = Yii::$app->request->post('image2');
            $model->image3 = Yii::$app->request->post('image3');
            $model->save(false);
        }
        Yii::$app->session->setFlash('success', 'Berita diperbarui.');
        return $this->redirect(['berita']);
    }

    public function actionBeritaDelete($id)
    {
        $model = News::findOne($id);
        if ($model) {
            $model->delete();
        }
        Yii::$app->session->setFlash('success', 'Berita dihapus.');
        return $this->redirect(['berita']);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $defaultAdmin = \app\models\Admin::findOne(['username' => 'admin1']);
        if ($defaultAdmin === null) {
            $defaultAdmin = new \app\models\Admin();
            $defaultAdmin->username = 'admin1';
            $defaultAdmin->password_hash = Yii::$app->security->generatePasswordHash('admin123');
            $defaultAdmin->auth_key = Yii::$app->security->generateRandomString();
            $defaultAdmin->save(false);
        }
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    private function isAdmin(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->username === 'admin1';
    }
}
