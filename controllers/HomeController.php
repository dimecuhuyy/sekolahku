<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Profile;
use app\models\News;
use app\models\Achievement;

class HomeController extends Controller
{
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
        $achModels = Achievement::find()->orderBy(['created_at' => SORT_DESC])->limit(3)->all();
        $achievements = [];
        foreach ($achModels as $m) {
            $images = array_values(array_filter([$m->image1, $m->image2, $m->image3]));
            $achievements[] = [
                'id' => (string)$m->id,
                'title' => $m->title,
                'achiever' => $m->achiever,
                'description' => $m->description,
                'images' => $images,
            ];
        }
        return $this->render('@app/views/site/index', [
            'profile' => $profile,
            'news' => $news,
            'achievements' => $achievements,
        ]);
    }
}
