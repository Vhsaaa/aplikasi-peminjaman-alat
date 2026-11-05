<?php
AuthController::requireRole('admin');

$totalUsers = User::count();
$totalAlat = Alat::count();
$totalKategori = Kategori::count();
$totalPeminjaman = Peminjaman::totalCount();
$totalPengembalian = Pengembalian::all();
$totalMenunggu = Peminjaman::pendingCount();
$logs = LogAktivitas::all();
$recentPeminjaman = Peminjaman::all();
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Dashboard Admin</h3>
                <p class="text-muted mb-0">Ringkasan sistem peminjaman alat</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Pengguna</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $totalUsers ?></h3>
                        </div>
                        <div class="stat-icon bg-primary-soft"><i class="fa-solid fa-users"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Alat</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $totalAlat ?></h3>
                        </div>
                        <div class="stat-icon bg-success-soft"><i class="fa-solid fa-toolbox"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Kategori</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $totalKategori ?></h3>
                        </div>
                        <div class="stat-icon bg-info-soft"><i class="fa-solid fa-tags"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Peminjaman</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $totalPeminjaman ?></h3>
                        </div>
                        <div class="stat-icon bg-warning-soft"><i class="fa-solid fa-clipboard-list"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Pengembalian</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= count($totalPengembalian) ?></h3>
                        </div>
                        <div class="stat-icon bg-success-soft"><i class="fa-solid fa-arrow-rotate-left"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Pengajuan Menunggu</div>
                            <h3 class="fw-bold mt-2 mb-0"><?= $totalMenunggu ?></h3>
                        </div>
                        <div class="stat-icon bg-danger-soft"><i class="fa-solid fa-hourglass-half"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-6">
                <div class="card-box p-4">
                    <h5 class="fw-bold mb-3">Grafik Peminjaman</h5>
                    <div class="bg-light rounded p-3 text-center text-muted" style="height: 220px; display:flex; align-items:center; justify-content:center;">
                        <div>
                            <i class="fa-solid fa-chart-column fa-2x mb-2"></i><br>
                            Grafik peminjaman akan ditampilkan di sini.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-box p-4">
                    <h5 class="fw-bold mb-3">Grafik Kategori Alat</h5>
                    <div class="bg-light rounded p-3 text-center text-muted" style="height: 220px; display:flex; align-items:center; justify-content:center;">
                        <div>
                            <i class="fa-solid fa-chart-pie fa-2x mb-2"></i><br>
                            Distribusi kategori alat.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-6">
                <div class="card-box p-4">
                    <h5 class="fw-bold mb-3">Aktivitas Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Aktivitas</th>
                                <th>Waktu</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($logs, 0, 5) as $log): ?>
                                <tr>
                                    <td><?= e($log['nama_lengkap']) ?></td>
                                    <td><?= e($log['aktivitas']) ?></td>
                                    <td><?= e($log['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-box p-4">
                    <h5 class="fw-bold mb-3">Peminjaman Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($recentPeminjaman, 0, 5) as $item): ?>
                                <tr>
                                    <td><?= e($item['kode_peminjaman']) ?></td>
                                    <td><?= e($item['nama_lengkap'] ?? 'Unknown') ?></td>
                                    <td><span class="badge bg-warning text-dark"><?= e($item['status']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
