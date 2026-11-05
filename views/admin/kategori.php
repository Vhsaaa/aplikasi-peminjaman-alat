<?php
AuthController::requireRole('admin');
$kategoris = Kategori::all();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (isset($_POST['save_kategori'])) {
    $nama = trim($_POST['nama_kategori'] ?? '');
    $desc = trim($_POST['deskripsi'] ?? '');
    if ($nama === '') {
        flash('error', 'Nama kategori wajib diisi.');
        redirect('index.php?page=admin-kategori');
    }
    if (!Kategori::create($nama, $desc)) {
        flash('error', 'Gagal menambah kategori.');
        redirect('index.php?page=admin-kategori');
    }
    flash('success', 'Kategori berhasil ditambah.');
    redirect('index.php?page=admin-kategori');
}

if (isset($_POST['update_kategori'])) {
    $id = (int) ($_POST['id_kategori'] ?? 0);
    $nama = trim($_POST['nama_kategori'] ?? '');
    $desc = trim($_POST['deskripsi'] ?? '');
    if ($id <= 0 || $nama === '') {
        flash('error', 'Data kategori tidak valid.');
        redirect('index.php?page=admin-kategori');
    }
    Kategori::update($id, $nama, $desc);
    flash('success', 'Kategori berhasil diubah.');
    redirect('index.php?page=admin-kategori');
}

if (isset($_GET['delete_kategori'])) {
    $id = (int) $_GET['delete_kategori'];
    Kategori::delete($id);
    flash('success', 'Kategori berhasil dihapus.');
    redirect('index.php?page=admin-kategori');
}
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Manajemen Kategori</h3>
                <p class="text-muted mb-0">Kelola jenis kategori alat</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKategori">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
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
                    <th>ID Kategori</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($kategoris as $kategori): ?>
                    <tr>
                        <td><?= e($kategori['id_kategori']) ?></td>
                        <td><?= e($kategori['nama_kategori']) ?></td>
                        <td><?= e($kategori['deskripsi']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editKategori<?= e($kategori['id_kategori']) ?>">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <a href="index.php?page=admin-kategori&delete_kategori=<?= e($kategori['id_kategori']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
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

<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="save_kategori" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php foreach ($kategoris as $kategori): ?>
<div class="modal fade" id="editKategori<?= e($kategori['id_kategori']) ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_kategori" value="<?= e($kategori['id_kategori']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" value="<?= e($kategori['nama_kategori']) ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= e($kategori['deskripsi']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update_kategori" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
