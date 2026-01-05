<?php
/** @var yii\web\View $this */
/** @var array $profile */

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;

$this->title = 'Profil Sekolah';
$this->params['breadcrumbs'][] = $this->title;
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->username === 'admin1';
?>
<div class="row mt-3">
    <div class="col-md-6 mb-3">
        <div class="card <?= $isAdmin ? 'border border-dark' : 'border-0' ?> h-100 position-relative">
            <?php if ($isAdmin): ?>
                <button class="btn btn-sm btn-warning position-absolute top-0 end-0 m-2" data-bs-toggle="modal" data-bs-target="#modalVisiMisi">Edit</button>
            <?php endif; ?>
            <div class="card-header bg-black text-white">Visi & Misi</div>
            <div class="card-body">
                <p class="card-text"><?= nl2br(Html::encode($profile['visiMisi'])) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card <?= $isAdmin ? 'border border-dark' : 'border-0' ?> h-100 position-relative">
            <?php if ($isAdmin): ?>
                <button class="btn btn-sm btn-warning position-absolute top-0 end-0 m-2" data-bs-toggle="modal" data-bs-target="#modalSejarah">Edit</button>
            <?php endif; ?>
            <div class="card-header bg-black text-white">Sejarah Sekolah</div>
            <div class="card-body">
                <p class="card-text"><?= nl2br(Html::encode($profile['sejarah'])) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card <?= $isAdmin ? 'border border-dark' : 'border-0' ?> h-100 position-relative">
            <?php if ($isAdmin): ?>
                <button class="btn btn-sm btn-warning position-absolute top-0 end-0 m-2" data-bs-toggle="modal" data-bs-target="#modalTelpon">Edit</button>
            <?php endif; ?>
            <div class="card-header bg-black text-white">Nomor Telpon</div>
            <div class="card-body">
                <p class="card-text"><?= Html::encode($profile['nomorTelpon']) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card <?= $isAdmin ? 'border border-dark' : 'border-0' ?> h-100 position-relative">
            <?php if ($isAdmin): ?>
                <button class="btn btn-sm btn-warning position-absolute top-0 end-0 m-2" data-bs-toggle="modal" data-bs-target="#modalAkreditasi">Edit</button>
            <?php endif; ?>
            <div class="card-header bg-black text-white">Akreditasi</div>
            <div class="card-body">
                <h3 class="text-danger"><?= Html::encode($profile['akreditasi']) ?></h3>
            </div>
        </div>
    </div>
</div>

<?php if ($isAdmin): ?>
<!-- Modals -->
<?php Modal::begin(['id' => 'modalVisiMisi', 'title' => 'Edit Visi & Misi']); ?>
<form method="post" action="<?= Yii::$app->urlManager->createUrl(['profile/update']) ?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <input type="hidden" name="field" value="visiMisi">
    <div class="mb-3">
        <textarea class="form-control" name="value" rows="5"><?= Html::encode($profile['visiMisi']) ?></textarea>
    </div>
    <button class="btn btn-danger" type="submit">Simpan</button>
</form>
<?php Modal::end(); ?>

<?php Modal::begin(['id' => 'modalSejarah', 'title' => 'Edit Sejarah Sekolah']); ?>
<form method="post" action="<?= Yii::$app->urlManager->createUrl(['profile/update']) ?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <input type="hidden" name="field" value="sejarah">
    <div class="mb-3">
        <textarea class="form-control" name="value" rows="5"><?= Html::encode($profile['sejarah']) ?></textarea>
    </div>
    <button class="btn btn-danger" type="submit">Simpan</button>
</form>
<?php Modal::end(); ?>

<?php Modal::begin(['id' => 'modalTelpon', 'title' => 'Edit Nomor Telpon']); ?>
<form method="post" action="<?= Yii::$app->urlManager->createUrl(['profile/update']) ?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <input type="hidden" name="field" value="nomorTelpon">
    <div class="mb-3">
        <input class="form-control" name="value" value="<?= Html::encode($profile['nomorTelpon']) ?>">
    </div>
    <button class="btn btn-danger" type="submit">Simpan</button>
</form>
<?php Modal::end(); ?>

<?php Modal::begin(['id' => 'modalAkreditasi', 'title' => 'Edit Akreditasi']); ?>
<form method="post" action="<?= Yii::$app->urlManager->createUrl(['profile/update']) ?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <input type="hidden" name="field" value="akreditasi">
    <div class="mb-3">
        <input class="form-control" name="value" value="<?= Html::encode($profile['akreditasi']) ?>">
    </div>
    <button class="btn btn-danger" type="submit">Simpan</button>
</form>
<?php Modal::end(); ?>
<?php endif; ?>
