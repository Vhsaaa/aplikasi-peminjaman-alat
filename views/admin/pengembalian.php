<?php
AuthController::requireRole('admin');
$items = Pengembalian::all();
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Data Pengembalian</h3>
                <p class="text-muted mb-0">Riwayat pengembalian alat</p>
            </div>
        </div>
        <div class="card-box p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal Real</th>
                        <th>Keterlambatan</th>
                        <th>Denda</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= e($item['kode_peminjaman']) ?></td>
                            <td><?= e($item['nama_lengkap']) ?></td>
                            <td><?= e($item['nama_alat']) ?></td>
                            <td><?= e($item['tanggal_kembali_real']) ?></td>
                            <td><?= e($item['keterlambatan']) ?> hari</td>
                            <td>Rp<?= number_format($item['denda'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
