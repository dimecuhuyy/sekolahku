<?php
/** @var yii\web\View $this */
/** @var array $items */

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;

$this->title = 'Berita';
$this->params['breadcrumbs'][] = $this->title;
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->username === 'admin1';
?>
<div class="d-flex justify-content-between align-items-center mt-3 mb-3">
    <h2 class="text-dark mb-0">Berita Sekolah</h2>
    <?php if ($isAdmin): ?>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambahBerita">Tambah Berita</button>
    <?php endif; ?>
</div>

<div class="row">
    <?php foreach ($items as $it): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100 <?= $isAdmin ? 'border-danger' : 'border-0' ?>">
                <div class="position-relative">
                    <?php if ($isAdmin): ?>
                        <div class="dropdown position-absolute top-0 start-0 m-2">
                            <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">⋮</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditBerita" data-id="<?= Html::encode($it['id']) ?>">Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#modalHapusBerita" data-id="<?= Html::encode($it['id']) ?>">Hapus</a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($it['images'])): ?>
                        <img class="card-img-top" src="<?= Html::encode($it['images'][0]) ?>" alt="Berita">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-danger"><?= Html::encode($it['title']) ?></h5>
                    <p class="card-text text-dark"><?= Html::encode(mb_strimwidth($it['description'] ?? '', 0, 160, '...')) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($items)): ?>
        <div class="col-12">
            <div class="alert alert-warning">Belum ada berita.</div>
        </div>
    <?php endif; ?>
</div>

<?php if ($isAdmin): ?>
<?php Modal::begin(['id' => 'modalTambahBerita', 'title' => 'Tambah Berita']); ?>
<form method="post" enctype="multipart/form-data" action="<?= Yii::$app->urlManager->createUrl(['news/create']) ?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <div class="mb-2">
        <label class="form-label">Judul Berita</label>
        <input class="form-control" name="title" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="4"></textarea>
    </div>
    <div class="mb-2">
        <label class="form-label">Foto (Upload) Maks 3</label>
        <input class="form-control mb-2" type="file" name="image1" accept="image/*">
        <input class="form-control mb-2" type="file" name="image2" accept="image/*">
        <input class="form-control mb-2" type="file" name="image3" accept="image/*">
    </div>
    <button class="btn btn-danger" type="submit">Simpan</button>
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
    <div class="form-text">Gunakan URL gambar. Untuk upload file diperlukan backend tambahan.</div>
</form>
<?php Modal::end(); ?>

<?php Modal::begin(['id' => 'modalEditBerita', 'title' => 'Edit Berita']); ?>
<form method="post" enctype="multipart/form-data" id="formEditBerita">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <div class="mb-2">
        <label class="form-label">Judul Berita</label>
        <input class="form-control" name="title" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="4"></textarea>
    </div>
    <div class="mb-2">
        <label class="form-label">Foto (Upload) Maks 3</label>
        <input class="form-control mb-2" type="file" name="image1" accept="image/*">
        <input class="form-control mb-2" type="file" name="image2" accept="image/*">
        <input class="form-control mb-2" type="file" name="image3" accept="image/*">
        <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
    </div>
    <button class="btn btn-danger" type="submit">Simpan</button>
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
</form>
<?php Modal::end(); ?>

<?php Modal::begin(['id' => 'modalHapusBerita', 'title' => 'Konfirmasi Hapus']); ?>
<form method="post" id="formHapusBerita">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <p>Apakah yakin ingin menghapus berita ini?</p>
    <button class="btn btn-danger" type="submit">Ya, Hapus</button>
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
    </form>
<?php Modal::end(); ?>
<?php endif; ?>

<?php
$itemsById = [];
foreach ($items as $it) {
    $itemsById[$it['id']] = $it;
}
$json = json_encode($itemsById);
$updateUrlBase = Yii::$app->urlManager->createUrl(['news/update', 'id' => 'REPLACE_ID']);
$deleteUrlBase = Yii::$app->urlManager->createUrl(['news/delete', 'id' => 'REPLACE_ID']);
?>
<script>
const beritaData = <?= $json ?>;
document.addEventListener('show.bs.modal', function (event) {
    const modal = event.target;
    const trigger = event.relatedTarget;
    if (!trigger) return;
    const id = trigger.getAttribute('data-id');
    if (modal.id === 'modalEditBerita') {
        const data = beritaData[id];
        if (!data) return;
        const form = document.getElementById('formEditBerita');
        form.action = '<?= $updateUrlBase ?>'.replace('REPLACE_ID', id);
        form.querySelector('input[name="title"]').value = data.title || '';
        form.querySelector('textarea[name="description"]').value = data.description || '';
        // File input tidak perlu prefill
    }
    if (modal.id === 'modalHapusBerita') {
        const form = document.getElementById('formHapusBerita');
        form.action = '<?= $deleteUrlBase ?>'.replace('REPLACE_ID', id);
    }
});
</script>
