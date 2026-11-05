<?php
AuthController::requireRole('peminjam');
$alats = Alat::getAvailable();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (isset($_POST['ajukan_peminjaman'])) {
    $idAlat = (int) ($_POST['id_alat'] ?? 0);
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $tanggalPinjam = $_POST['tanggal_pinjam'] ?? null;
    $tanggalKembali = $_POST['tanggal_rencana_kembali'] ?? null;
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (!$idAlat || !$tanggalPinjam || !$tanggalKembali || $jumlah < 1) {
        flash('error', 'Semua input wajib diisi dan jumlah minimal 1.');
        redirect('index.php?page=peminjam-pengajuan');
    }

    if (strtotime($tanggalKembali) <= strtotime($tanggalPinjam)) {
        flash('error', 'Tanggal kembali harus setelah tanggal peminjaman.');
        redirect('index.php?page=peminjam-pengajuan');
    }

    $alat = Alat::findById($idAlat);
    if (!$alat) {
        flash('error', 'Alat tidak ditemukan.');
        redirect('index.php?page=peminjam-pengajuan');
    }

    if ($jumlah > $alat['stok']) {
        flash('error', 'Stok Tidak Cukup');
        redirect('index.php?page=peminjam-pengajuan');
    }

    if (!Peminjaman::create($_SESSION['user']['id_user'], $idAlat, $jumlah, $tanggalPinjam, $tanggalKembali, $keterangan)) {
        flash('error', 'Gagal mengajukan peminjaman.');
        redirect('index.php?page=peminjam-pengajuan');
    }

    AuthController::logActivity($_SESSION['user']['id_user'], 'Mengajukan peminjaman', 'Pengajuan peminjaman alat berhasil dibuat.');
    flash('success', 'Pengajuan peminjaman berhasil dikirim.');
    redirect('index.php?page=peminjam-pengajuan');
}
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Ajukan Peminjaman</h3>
                <p class="text-muted mb-0">Isi form peminjaman alat</p>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                <?= e($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card-box p-4">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" value="<?= e($_SESSION['user']['nama_lengkap']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pilih Alat</label>
                        <select name="id_alat" class="form-select" required>
                            <option value="">-- Pilih Alat --</option>
                            <?php foreach ($alats as $alat): ?>
                                <option value="<?= e($alat['id_alat']) ?>"><?= e($alat['nama_alat']) ?> (Stok: <?= e($alat['stok']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Alat</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Peminjaman</label>
                        <input type="date" name="tanggal_pinjam" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Rencana Kembali</label>
                        <input type="date" name="tanggal_rencana_kembali" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="ajukan_peminjaman" class="btn btn-primary">Ajukan Peminjaman</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
