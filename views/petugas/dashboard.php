<?php
AuthController::requireRole('petugas');
$pending = Peminjaman::pendingCount();
$approved = Peminjaman::approvedCount();
$finished = Peminjaman::finishedCount();
$totalDenda = Pengembalian::totalDenda();
$pendingList = Peminjaman::pending();
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Dashboard Petugas</h3>
                <p class="text-muted mb-0">Pantau peminjaman, persetujuan, dan pengembalian</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Pengajuan Menunggu</div>
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
                            <div class="text-muted small">Pengembalian</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $finished ?></h3>
                        </div>
                        <div class="stat-icon bg-info-soft"><i class="fa-solid fa-arrow-rotate-left"></i></div>
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

        <div class="card-box p-4 mt-4">
            <h5 class="fw-bold mb-3">Pengajuan Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (array_slice($pendingList, 0, 5) as $item): ?>
                        <tr>
                            <td><?= e($item['kode_peminjaman']) ?></td>
                            <td><?= e($item['nama_lengkap']) ?></td>
                            <td><?= e($item['nama_alat']) ?></td>
                            <td><?= e($item['jumlah']) ?></td>
                            <td><span class="badge bg-warning text-dark"><?= e($item['status']) ?></span></td>
                            <td>
                                <a href="index.php?page=petugas-persetujuan" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
