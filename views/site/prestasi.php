<?php
/** @var yii\web\View $this */
/** @var array $items */

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;

$this->title = 'Prestasi';
$this->params['breadcrumbs'][] = $this->title;
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->username === 'admin1';
?>
<div class="d-flex justify-content-between align-items-center mt-3 mb-3">
    <h2 class="text-dark mb-0">Prestasi Sekolah</h2>
    <?php if ($isAdmin): ?>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambahPrestasi">Tambah Prestasi</button>
    <?php endif; ?>
</div>

<div class="row">
    <?php foreach ($items as $it): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100 <?= $isAdmin ? 'border-warning' : 'border-0' ?>">
                <div class="position-relative">
                    <?php if ($isAdmin): ?>
                        <div class="dropdown position-absolute top-0 start-0 m-2">
                            <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">⋮</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditPrestasi" data-id="<?= Html::encode($it['id']) ?>">Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#modalHapusPrestasi" data-id="<?= Html::encode($it['id']) ?>">Hapus</a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($it['images'])): ?>
                        <img class="card-img-top" src="<?= Html::encode($it['images'][0]) ?>" alt="Prestasi">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-danger"><?= Html::encode($it['title']) ?></h5>
                    <p class="text-muted mb-1">Peraih: <?= Html::encode($it['achiever']) ?></p>
                    <p class="card-text text-dark"><?= Html::encode(mb_strimwidth($it['description'] ?? '', 0, 140, '...')) ?></p>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDetailPrestasi" data-id="<?= Html::encode($it['id']) ?>">Lihat Detail</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($items)): ?>
        <div class="col-12">
            <div class="alert alert-warning">Belum ada data prestasi.</div>
        </div>
    <?php endif; ?>
</div>

<?php if ($isAdmin): ?>
<?php Modal::begin(['id' => 'modalTambahPrestasi', 'title' => 'Tambah Prestasi']); ?>
<form method="post" enctype="multipart/form-data" action="<?= Yii::$app->urlManager->createUrl(['achievement/create']) ?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <div class="mb-2">
        <label class="form-label">Judul Prestasi</label>
        <input class="form-control" name="title" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Nama Peraih Prestasi</label>
        <input class="form-control" name="achiever" required>
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

<?php Modal::begin(['id' => 'modalEditPrestasi', 'title' => 'Edit Prestasi']); ?>
<form method="post" enctype="multipart/form-data" id="formEditPrestasi">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <div class="mb-2">
        <label class="form-label">Judul Prestasi</label>
        <input class="form-control" name="title" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Nama Peraih Prestasi</label>
        <input class="form-control" name="achiever" required>
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

<?php Modal::begin(['id' => 'modalHapusPrestasi', 'title' => 'Konfirmasi Hapus']); ?>
<form method="post" id="formHapusPrestasi">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
    <p>Apakah yakin ingin menghapus prestasi ini?</p>
    <button class="btn btn-danger" type="submit">Ya, Hapus</button>
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
    </form>
<?php Modal::end(); ?>
<?php endif; ?>

<?php Modal::begin(['id' => 'modalDetailPrestasi', 'title' => 'Detail Prestasi']); ?>
<div id="detailPrestasiContent">
    <div class="text-muted">Memuat...</div>
</div>
<?php Modal::end(); ?>

<?php
$itemsById = [];
foreach ($items as $it) {
    $itemsById[$it['id']] = $it;
}
$json = json_encode($itemsById);
$updateUrlBase = Yii::$app->urlManager->createUrl(['achievement/update', 'id' => 'REPLACE_ID']);
$deleteUrlBase = Yii::$app->urlManager->createUrl(['achievement/delete', 'id' => 'REPLACE_ID']);
?>
<script>
const prestasiData = <?= $json ?>;
document.addEventListener('show.bs.modal', function (event) {
    const modal = event.target;
    const trigger = event.relatedTarget;
    if (!trigger) return;
    const id = trigger.getAttribute('data-id');
    if (modal.id === 'modalEditPrestasi') {
        const data = prestasiData[id];
        if (!data) return;
        const form = document.getElementById('formEditPrestasi');
        form.action = '<?= $updateUrlBase ?>'.replace('REPLACE_ID', id);
        form.querySelector('input[name="title"]').value = data.title || '';
        form.querySelector('input[name="achiever"]').value = data.achiever || '';
        form.querySelector('textarea[name="description"]').value = data.description || '';
        // File input tidak perlu prefill
    }
    if (modal.id === 'modalHapusPrestasi') {
        const form = document.getElementById('formHapusPrestasi');
        form.action = '<?= $deleteUrlBase ?>'.replace('REPLACE_ID', id);
    }
    if (modal.id === 'modalDetailPrestasi') {
        const data = prestasiData[id];
        const container = document.getElementById('detailPrestasiContent');
        if (!data) { container.innerHTML = '<div>Data tidak ditemukan</div>'; return; }
        let imagesHtml = '';
        if (data.images && data.images.length) {
            imagesHtml = `<div id="carouselPrestasi" class="carousel slide mb-3" data-bs-ride="carousel">
                <div class="carousel-inner">
                    ${data.images.map((src, idx) => `<div class="carousel-item ${idx===0?'active':''}">
                        <img class="d-block w-100" src="${src}" alt="foto">
                    </div>`).join('')}
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselPrestasi" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselPrestasi" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>`;
        }
        container.innerHTML = `
            ${imagesHtml}
            <h5 class="text-danger">${data.title || ''}</h5>
            <p class="text-muted">Peraih: ${data.achiever || ''}</p>
            <p>${(data.description || '').replace(/\\n/g,'<br>')}</p>
        `;
    }
});
</script>
