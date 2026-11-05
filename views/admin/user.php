<?php
AuthController::requireRole('admin');
$users = User::all();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (isset($_POST['save_user'])) {
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'peminjam';

    if ($nama === '' || $username === '') {
        flash('error', 'Nama lengkap dan username wajib diisi.');
        redirect('index.php?page=admin-user');
    }

    if (User::findByUsername($username)) {
        flash('error', 'Username sudah digunakan.');
        redirect('index.php?page=admin-user');
    }

    if (!User::create($nama, $username, $password, $role)) {
        flash('error', 'Gagal menambah user.');
        redirect('index.php?page=admin-user');
    }

    flash('success', 'User berhasil ditambah.');
    redirect('index.php?page=admin-user');
}

if (isset($_POST['update_user'])) {
    $id = (int) ($_POST['id_user'] ?? 0);
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $role = $_POST['role'] ?? 'peminjam';
    $password = $_POST['password'] ?? '';

    if ($id <= 0 || $nama === '' || $username === '') {
        flash('error', 'Data user tidak valid.');
        redirect('index.php?page=admin-user');
    }

    if (!User::update($id, $nama, $username, $role, $password)) {
        flash('error', 'Gagal mengubah user.');
        redirect('index.php?page=admin-user');
    }

    flash('success', 'User berhasil diubah.');
    redirect('index.php?page=admin-user');
}

if (isset($_GET['delete_user'])) {
    $id = (int) $_GET['delete_user'];
    User::delete($id);
    flash('success', 'User berhasil dihapus.');
    redirect('index.php?page=admin-user');
}
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Manajemen User</h3>
                <p class="text-muted mb-0">Kelola akun admin, petugas, dan peminjam</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser">
                <i class="fa-solid fa-plus"></i> Tambah User
            </button>
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
                        <th>ID User</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= e($user['id_user']) ?></td>
                            <td><?= e($user['nama_lengkap']) ?></td>
                            <td><?= e($user['username']) ?></td>
                            <td><span class="badge bg-primary"><?= e($user['role']) ?></span></td>
                            <td><?= e($user['created_at']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editUser<?= e($user['id_user']) ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="index.php?page=admin-user&delete_user=<?= e($user['id_user']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">
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
</div>

<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="peminjam">Peminjam</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="save_user" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php foreach ($users as $user): ?>
<div class="modal fade" id="editUser<?= e($user['id_user']) ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_user" value="<?= e($user['id_user']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= e($user['nama_lengkap']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= e($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru (opsional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                            <option value="peminjam" <?= $user['role'] === 'peminjam' ? 'selected' : '' ?>>Peminjam</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update_user" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
