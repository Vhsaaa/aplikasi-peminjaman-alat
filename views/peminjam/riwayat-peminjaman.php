<?php
AuthController::requireRole('peminjam');
$myHistory = Peminjaman::byUser($_SESSION['user']['id_user']);
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Riwayat Peminjaman</h3>
                <p class="text-muted mb-0">Semua transaksi peminjaman Anda</p>
            </div>
        </div>

        <div class="card-box p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($myHistory as $item): ?>
                        <tr>
                            <td><?= e($item['kode_peminjaman']) ?></td>
                            <td><?= e($item['nama_alat']) ?></td>
                            <td><?= e($item['jumlah']) ?></td>
                            <td><?= e($item['tanggal_pinjam']) ?></td>
                            <td><?= e($item['tanggal_rencana_kembali']) ?></td>
                            <td><span class="badge bg-info"><?= e($item['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
