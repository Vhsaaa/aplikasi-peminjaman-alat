<?php
AuthController::requireRole('admin');
$logs = LogAktivitas::all();
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Log Aktivitas</h3>
                <p class="text-muted mb-0">Riwayat aktivitas seluruh user</p>
            </div>
        </div>

        <div class="card-box p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Nama User</th>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= e($log['nama_lengkap']) ?></td>
                            <td><?= e($log['aktivitas']) ?></td>
                            <td><?= e($log['created_at']) ?></td>
                            <td><?= e($log['keterangan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
