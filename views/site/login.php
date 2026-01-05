<?php
/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\Html;

$this->title = 'Login Admin';
$this->params['breadcrumbs'] = [];
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>
<style>
.login-hero {
    min-height: 100vh;
    background: linear-gradient(135deg, #000 0%, #c40000 50%, #ffcc00 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}
.login-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(0,0,0,.25);
    overflow: hidden;
    max-width: 900px;
    width: 100%;
}
.login-left {
    background: #000;
    color: #fff;
    padding: 40px;
}
.login-left h1 {
    font-weight: 800;
    margin-bottom: 12px;
}
.login-left p {
    opacity: .85;
}
.login-right {
    padding: 40px;
}
.form-control {
    border-radius: 10px;
    padding: 12px 14px;
}
.btn-login {
    background: #c40000;
    border-color: #c40000;
    color: #fff;
    border-radius: 10px;
    padding: 12px 16px;
    font-weight: 600;
    width: 100%;
}
.brand-badge {
    display: inline-block;
    background: #ffcc00;
    color: #000;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 8px;
    margin-bottom: 16px;
}
.helper {
    color: #666;
    font-size: .9rem;
    margin-top: 12px;
}
@media (max-width: 768px) {
    .login-left, .login-right { padding: 24px; }
}
</style>

<div class="login-hero">
    <div class="login-card">
        <div class="row g-0">
            <div class="col-md-5 login-left">
                <div class="brand-badge">SDN 2 PADOKAN</div>
                <h1>Portal Admin</h1>
                <p>Kelola konten Profil, Prestasi, dan Berita dengan mudah.</p>
                <ul class="mt-3" style="padding-left:18px">
                    <li>Edit card Profil (visi misi, sejarah, telpon, akreditasi)</li>
                    <li>Tambah/Edit/Hapus Prestasi dan Berita</li>
                    <li>Upload gambar langsung dari perangkat</li>
                </ul>
            </div>
            <div class="col-md-7 login-right">
                <h3 class="mb-3">Masuk Akun Admin</h3>
                <form id="login-form" method="post">
                    <input type="hidden" name="<?= $csrfParam ?>" value="<?= $csrfToken ?>">
                    <?php if (!empty($model->errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($model->errors as $errs): ?>
                                <?php foreach ($errs as $msg): ?>
                                    <div><?= Html::encode($msg) ?></div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input class="form-control" name="LoginForm[username]" value="<?= Html::encode($model->username) ?>" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control" type="password" name="LoginForm[password]" value="<?= Html::encode($model->password) ?>">
                    </div>
                    <button class="btn btn-login" type="submit" name="login-button">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
