<?php
AuthController::requireRole('peminjam');
$mine = Peminjaman::byUser($_SESSION['user']['id_user']);
$pending = 0;
$approved = 0;
$finished = 0;
$totalDenda = 0;
foreach ($mine as $item) {
    if ($item['status'] === 'menunggu') $pending++;
    if ($item['status'] === 'disetujui') $approved++;
    if ($item['status'] === 'selesai') $finished++;
}
$alats = Alat::getAvailable();
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Dashboard Peminjam</h3>
                <p class="text-muted mb-0">Riwayat dan status peminjaman alat Anda</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Pengajuan</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= count($mine) ?></h3>
                        </div>
                        <div class="stat-icon bg-primary-soft"><i class="fa-solid fa-file-lines"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Menunggu</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $pending ?></h3>
                        </div>
                        <div class="stat-icon bg-warning-soft"><i class="fa-solid fa-hourglass-half"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Disetujui</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $approved ?></h3>
                        </div>
                        <div class="stat-icon bg-success-soft"><i class="fa-solid fa-check"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Denda</div>
                            <h3 class="fw-bold mt-2 mb-0">Rp<?= number_format($totalDenda, 0, ',', '.') ?></h3>
                        </div>
                        <div class="stat-icon bg-danger-soft"><i class="fa-solid fa-money-bill-wave"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-7">
                <div class="card-box p-4">
                    <h5 class="fw-bold mb-3">Peminjaman Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Alat</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($mine, 0, 5) as $item): ?>
                                <tr>
                                    <td><?= e($item['kode_peminjaman']) ?></td>
                                    <td><?= e($item['nama_alat']) ?></td>
                                    <td><span class="badge bg-info"><?= e($item['status']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-box p-4">
                    <h5 class="fw-bold mb-3">Alat Tersedia</h5>
                    <?php foreach (array_slice($alats, 0, 4) as $alat): ?>
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong><?= e($alat['nama_alat']) ?></strong>
                                <span class="badge bg-success">Stok: <?= e($alat['stok']) ?></span>
                            </div>
                            <small class="text-muted"><?= e($alat['nama_kategori']) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
