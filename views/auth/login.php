<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Peminjaman Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0b1f3a 0%, #1d4e89 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .login-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }
        .left-panel {
            background: #f0f7ff;
            padding: 50px 40px;
        }
        .right-panel {
            padding: 50px 40px;
        }
        .brand {
            color: #123d72;
            font-weight: 700;
        }
        .btn-primary {
            background-color: #123d72;
            border-color: #123d72;
        }
        .btn-primary:hover {
            background-color: #0e2f5d;
            border-color: #0e2f5d;
        }
        .password-group .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-box row g-0">
        <div class="col-md-6 left-panel">
            <div class="d-flex flex-column justify-content-center h-100">
                <div class="mb-4 text-center">
                    <i class="fa-solid fa-screwdriver-wrench fa-3x text-primary"></i>
                </div>
                <h2 class="brand text-center mb-3">APLIKASI PEMINJAMAN ALAT</h2>
                <p class="text-muted text-center mb-4">Sistem Informasi Manajemen Peminjaman dan Pengembalian Alat</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-0 bg-transparent"><i class="fa-solid fa-circle-check text-success me-2"></i>Manajemen alat</li>
                    <li class="list-group-item border-0 bg-transparent"><i class="fa-solid fa-circle-check text-success me-2"></i>Transaksi peminjaman</li>
                    <li class="list-group-item border-0 bg-transparent"><i class="fa-solid fa-circle-check text-success me-2"></i>Pengembalian dan denda</li>
                    <li class="list-group-item border-0 bg-transparent"><i class="fa-solid fa-circle-check text-success me-2"></i>Role admin, petugas, peminjam</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 right-panel">
            <h3 class="fw-bold mb-4 text-center">Login Sistem</h3>
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                    <?= e($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form method="POST" action="index.php?action=login">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                </div>
                <div class="mb-3 password-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="mt-4 text-center small text-muted">
                Demo akun: admin / petugas / peminjam<br>
                Password default: <strong>admin123</strong>, <strong>petugas123</strong>, <strong>peminjam123</strong>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.toggle-password').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
</body>
</html>
