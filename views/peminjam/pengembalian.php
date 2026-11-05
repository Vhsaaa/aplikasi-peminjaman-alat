<?php
AuthController::requireRole('peminjam');
$history = Peminjaman::byUser($_SESSION['user']['id_user']);
$currentDenda = 0;
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Pengembalian Alat</h3>
                <p class="text-muted mb-0">Status pengembalian Anda</p>
            </div>
        </div>

        <div class="card-box p-4">
            <table class="table table-hover align-middle mb-0">
                <thead>
                <tr>
                    <th>Kode</th>
                    <th>Alat</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Denda</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($history as $item): ?>
                    <tr>
                        <td><?= e($item['kode_peminjaman']) ?></td>
                        <td><?= e($item['nama_alat']) ?></td>
                        <td><?= e($item['jumlah']) ?></td>
                        <td><span class="badge bg-info"><?= e($item['status']) ?></span></td>
                        <td>Rp<?= number_format($currentDenda, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
