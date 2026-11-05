<?php
AuthController::requireRole('petugas');
$pendingList = Peminjaman::pending();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (isset($_POST['approve'])) {
    $id = (int) ($_POST['id_peminjaman'] ?? 0);
    $userId = $_SESSION['user']['id_user'];
    Peminjaman::approve($id, $userId);
    AuthController::logActivity($userId, 'Menyetujui peminjaman', 'Persetujuan peminjaman berhasil');
    flash('success', 'Peminjaman berhasil disetujui.');
    redirect('index.php?page=petugas-persetujuan');
}

if (isset($_POST['reject'])) {
    $id = (int) ($_POST['id_peminjaman'] ?? 0);
    $reason = trim($_POST['alasan_penolakan'] ?? '');
    Peminjaman::reject($id, $reason ?: 'Tidak memenuhi syarat');
    AuthController::logActivity($_SESSION['user']['id_user'], 'Menolak peminjaman', 'Peminjaman ditolak.');
    flash('success', 'Peminjaman berhasil ditolak.');
    redirect('index.php?page=petugas-persetujuan');
}
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Persetujuan Peminjaman</h3>
                <p class="text-muted mb-0">Daftar pengajuan yang menunggu persetujuan</p>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                <?= e($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card-box p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pendingList as $item): ?>
                        <tr>
                            <td><?= e($item['kode_peminjaman']) ?></td>
                            <td><?= e($item['nama_lengkap']) ?></td>
                            <td><?= e($item['nama_alat']) ?></td>
                            <td><?= e($item['jumlah']) ?></td>
                            <td><?= e($item['tanggal_pinjam']) ?></td>
                            <td><?= e($item['tanggal_rencana_kembali']) ?></td>
                            <td><span class="badge bg-warning text-dark"><?= e($item['status']) ?></span></td>
                            <td>
                                <form method="POST" class="d-inline-block">
                                    <input type="hidden" name="id_peminjaman" value="<?= e($item['id_peminjaman']) ?>">
                                    <button type="submit" name="approve" class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#reject<?= e($item['id_peminjaman']) ?>">Tolak</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach ($pendingList as $item): ?>
<div class="modal fade" id="reject<?= e($item['id_peminjaman']) ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_peminjaman" value="<?= e($item['id_peminjaman']) ?>">
                    <label class="form-label">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="reject" class="btn btn-danger">Tolak</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
