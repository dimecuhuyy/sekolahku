<?php
/** @var yii\web\View $this */
/** @var array $profile */
/** @var array $news */
/** @var array $achievements */

use yii\bootstrap5\Html;

$this->title = 'Beranda';
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->username === 'admin1';
?>
<div class="site-index">
    <div class="mt-5 mb-4 text-center">
        <h1 class="display-5 text-danger fw-bold">Selamat Datang di SDN 2 PADOKAN</h1>
        <p class="lead text-dark"><?= Html::encode($profile['visiMisi']) ?></p>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card <?= $isAdmin ? 'border border-dark' : 'border-0' ?> mb-3">
                <div class="card-header bg-black text-white">Profil Singkat</div>
                <div class="card-body">
                    <p class="card-text"><?= nl2br(Html::encode($profile['profilSingkat'])) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card <?= $isAdmin ? 'border border-warning' : 'border-0' ?> mb-3">
                <div class="card-header bg-warning text-dark">Akreditasi</div>
                <div class="card-body">
                    <h3 class="text-danger mb-0"><?= Html::encode($profile['akreditasi']) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-3 text-dark">Prestasi Terbaru</h2>
    <div class="row">
        <?php foreach ($achievements as $item): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100 <?= $isAdmin ? 'border-danger' : 'border-0' ?>">
                    <?php if (!empty($item['images'])): ?>
                        <img class="card-img-top" src="<?= Html::encode($item['images'][0]) ?>" alt="Prestasi">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title text-danger"><?= Html::encode($item['title']) ?></h5>
                        <p class="card-text text-muted">Peraih: <?= Html::encode($item['achiever'] ?? '') ?></p>
                        <p class="card-text text-dark"><?= Html::encode(mb_strimwidth($item['description'] ?? '', 0, 120, '...')) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($achievements)): ?>
            <div class="col-12">
                <div class="alert alert-warning">Belum ada prestasi.</div>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="mb-3 text-dark">Berita Terbaru</h2>
    <div class="row">
        <?php foreach ($news as $item): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100 <?= $isAdmin ? 'border-danger' : 'border-0' ?>">
                    <?php if (!empty($item['images'])): ?>
                        <img class="card-img-top" src="<?= Html::encode($item['images'][0]) ?>" alt="Berita">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title text-danger"><?= Html::encode($item['title']) ?></h5>
                        <p class="card-text text-dark"><?= Html::encode(mb_strimwidth($item['description'] ?? '', 0, 120, '...')) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($news)): ?>
            <div class="col-12">
                <div class="alert alert-warning">Belum ada berita.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
