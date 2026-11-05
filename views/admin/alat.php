<?php
AuthController::requireRole('admin');
$kategoris = Kategori::all();
$alats = Alat::all();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (isset($_POST['save_alat'])) {
    $idKategori = (int) ($_POST['id_kategori'] ?? 0);
    $nama = trim($_POST['nama_alat'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $stok = (int) ($_POST['stok'] ?? 0);
    $kondisi = $_POST['kondisi'] ?? 'Baik';
    $gambar = 'default.jpg';

    if ($nama === '' || $idKategori <= 0 || $stok < 0) {
        flash('error', 'Nama alat, kategori, dan stok tidak valid.');
        redirect('index.php?page=admin-alat');
    }

    if (!Alat::create($idKategori, $nama, $deskripsi, $stok, $kondisi, $gambar)) {
        flash('error', 'Gagal menambah alat.');
        redirect('index.php?page=admin-alat');
    }

    flash('success', 'Alat berhasil ditambah.');
    redirect('index.php?page=admin-alat');
}

if (isset($_POST['update_alat'])) {
    $id = (int) ($_POST['id_alat'] ?? 0);
    $idKategori = (int) ($_POST['id_kategori'] ?? 0);
    $nama = trim($_POST['nama_alat'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $stok = (int) ($_POST['stok'] ?? 0);
    $kondisi = $_POST['kondisi'] ?? 'Baik';

    if ($id <= 0 || $nama === '' || $idKategori <= 0 || $stok < 0) {
        flash('error', 'Data alat tidak valid.');
        redirect('index.php?page=admin-alat');
    }

    Alat::update($id, $idKategori, $nama, $deskripsi, $stok, $kondisi);
    flash('success', 'Alat berhasil diubah.');
    redirect('index.php?page=admin-alat');
}

if (isset($_GET['delete_alat'])) {
    $id = (int) $_GET['delete_alat'];
    Alat::delete($id);
    flash('success', 'Alat berhasil dihapus.');
    redirect('index.php?page=admin-alat');
}
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Manajemen Alat</h3>
                <p class="text-muted mb-0">Kelola data alat, stok, dan kondisi</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAlat">
                <i class="fa-solid fa-plus"></i> Tambah Alat
            </button>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                <?= e($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card-box p-4">
            <table class="table table-hover align-middle mb-0">
                <thead>
                <tr>
                    <th>ID Alat</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Stok</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($alats as $alat): ?>
                    <tr>
                        <td><?= e($alat['id_alat']) ?></td>
                        <td><?= e($alat['nama_alat']) ?></td>
                        <td><?= e($alat['nama_kategori']) ?></td>
                        <td><?= e($alat['deskripsi']) ?></td>
                        <td><?= e($alat['stok']) ?></td>
                        <td><span class="badge bg-success"><?= e($alat['kondisi']) ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editAlat<?= e($alat['id_alat']) ?>">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <a href="index.php?page=admin-alat&delete_alat=<?= e($alat['id_alat']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus alat ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAlat" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <?php foreach ($kategoris as $kategori): ?>
                                    <option value="<?= e($kategori['id_kategori']) ?>"><?= e($kategori['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" min="0" value="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Perlu Servis">Perlu Servis</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="save_alat" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php foreach ($alats as $alat): ?>
<div class="modal fade" id="editAlat<?= e($alat['id_alat']) ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_alat" value="<?= e($alat['id_alat']) ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control" value="<?= e($alat['nama_alat']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <?php foreach ($kategoris as $kategori): ?>
                                    <option value="<?= e($kategori['id_kategori']) ?>" <?= $alat['id_kategori'] == $kategori['id_kategori'] ? 'selected' : '' ?>><?= e($kategori['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" min="0" value="<?= e($alat['stok']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik" <?= $alat['kondisi'] == 'Baik' ? 'selected' : '' ?>>Baik</option>
                                <option value="Rusak" <?= $alat['kondisi'] == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                                <option value="Perlu Servis" <?= $alat['kondisi'] == 'Perlu Servis' ? 'selected' : '' ?>>Perlu Servis</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control"><?= e($alat['deskripsi']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update_alat" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
