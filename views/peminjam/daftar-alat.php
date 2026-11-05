<?php
AuthController::requireRole('peminjam');
$alats = Alat::getAvailable();
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Daftar Alat</h3>
                <p class="text-muted mb-0">Alat yang tersedia untuk dipinjam</p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($alats as $alat): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/<?= e($alat['gambar']) ?: 'default.jpg' ?>" class="card-img-top" alt="<?= e($alat['nama_alat']) ?>" style="height:180px; object-fit:cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0"><?= e($alat['nama_alat']) ?></h5>
                                <span class="badge bg-success">Stok: <?= e($alat['stok']) ?></span>
                            </div>
                            <p class="text-muted small mb-2"><i class="fa-solid fa-tag"></i> <?= e($alat['nama_kategori']) ?></p>
                            <p class="card-text"><?= e($alat['deskripsi']) ?></p>
                            <div class="mb-2"><strong>Kondisi:</strong> <?= e($alat['kondisi']) ?></div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailAlat<?= e($alat['id_alat']) ?>">Detail</button>
                                <a href="index.php?page=peminjam-pengajuan&id_alat=<?= e($alat['id_alat']) ?>" class="btn btn-primary btn-sm">Pinjam</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php foreach ($alats as $alat): ?>
<div class="modal fade" id="detailAlat<?= e($alat['id_alat']) ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= e($alat['nama_alat']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?= e($alat['deskripsi']) ?></p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Kategori: <?= e($alat['nama_kategori']) ?></li>
                    <li class="list-group-item">Stok: <?= e($alat['stok']) ?></li>
                    <li class="list-group-item">Kondisi: <?= e($alat['kondisi']) ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
