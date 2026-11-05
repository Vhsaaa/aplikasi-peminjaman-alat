<?php
AuthController::requireRole('petugas');
$approved = Peminjaman::approved();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (isset($_POST['proses_pengembalian'])) {
    $idPeminjaman = (int) ($_POST['id_peminjaman'] ?? 0);
    $tanggalReal = $_POST['tanggal_kembali_real'] ?? null;
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($idPeminjaman <= 0 || !$tanggalReal) {
        flash('error', 'Data pengembalian tidak valid.');
        redirect('index.php?page=petugas-pengembalian');
    }

    $peminjaman = Peminjaman::findById($idPeminjaman);
    if (!$peminjaman) {
        flash('error', 'Data peminjaman tidak ditemukan.');
        redirect('index.php?page=petugas-pengembalian');
    }

    $keterlambatan = max(0, (int) ((strtotime($tanggalReal) - strtotime($peminjaman['tanggal_rencana_kembali'])) / 86400));
    $denda = $keterlambatan > 0 ? $keterlambatan * 5000 : 0;

    if (Pengembalian::findByPeminjaman($idPeminjaman)) {
        flash('error', 'Transaksi pengembalian sudah diproses.');
        redirect('index.php?page=petugas-pengembalian');
    }

    Pengembalian::create($idPeminjaman, $tanggalReal, $keterlambatan, $denda, $keterangan);
    Peminjaman::complete($idPeminjaman);
    AuthController::logActivity($_SESSION['user']['id_user'], 'Memproses pengembalian', 'Pengembalian alat dilakukan dengan denda Rp' . number_format($denda, 0, ',', '.'));
    flash('success', 'Pengembalian berhasil diproses. Denda: Rp' . number_format($denda, 0, ',', '.'));
    redirect('index.php?page=petugas-pengembalian');
}
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Pengembalian Alat</h3>
                <p class="text-muted mb-0">Proses pengembalian dan perhitungan denda</p>
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
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($approved as $item): ?>
                        <tr>
                            <td><?= e($item['kode_peminjaman']) ?></td>
                            <td><?= e($item['nama_lengkap']) ?></td>
                            <td><?= e($item['nama_alat']) ?></td>
                            <td><?= e($item['jumlah']) ?></td>
                            <td><?= e($item['tanggal_rencana_kembali']) ?></td>
                            <td><span class="badge bg-success"><?= e($item['status']) ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#return<?= e($item['id_peminjaman']) ?>">Proses</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach ($approved as $item): ?>
<div class="modal fade" id="return<?= e($item['id_peminjaman']) ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengembalian Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_peminjaman" value="<?= e($item['id_peminjaman']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Kode Peminjaman</label>
                        <input type="text" class="form-control" value="<?= e($item['kode_peminjaman']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kembali Real</label>
                        <input type="date" name="tanggal_kembali_real" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="proses_pengembalian" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
