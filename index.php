<?php
ob_start();
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Kategori.php';
require_once __DIR__ . '/models/Alat.php';
require_once __DIR__ . '/models/Peminjaman.php';
require_once __DIR__ . '/models/Pengembalian.php';
require_once __DIR__ . '/models/LogAktivitas.php';

$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? null;

if ($action === 'login') {
    AuthController::login();
}

if ($action === 'logout') {
    AuthController::logout();
}

if ($page === 'login') {
    require __DIR__ . '/views/auth/login.php';
    exit;
}

if (!isset($_SESSION['user'])) {
    flash('error', 'Silakan login terlebih dahulu.');
    redirect('index.php?page=login');
}

$user = $_SESSION['user'];

if ($page === 'petugas-laporan-pdf') {
    AuthController::requireRole('petugas');
    require __DIR__ . '/views/petugas/laporan-pdf.php';
    exit;
}

require_once __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/views/layouts/sidebar.php';
require_once __DIR__ . '/views/layouts/navbar.php';

switch ($page) {
    case 'admin-dashboard':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/dashboard.php';
        break;
    case 'admin-user':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/user.php';
        break;
    case 'admin-alat':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/alat.php';
        break;
    case 'admin-kategori':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/kategori.php';
        break;
    case 'admin-log':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/log-aktivitas.php';
        break;
    case 'admin-peminjaman':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/peminjaman.php';
        break;
    case 'admin-pengembalian':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/pengembalian.php';
        break;
    case 'admin-profil':
        AuthController::requireRole('admin');
        require __DIR__ . '/views/admin/profil.php';
        break;
    case 'petugas-dashboard':
        AuthController::requireRole('petugas');
        require __DIR__ . '/views/petugas/dashboard.php';
        break;
    case 'petugas-persetujuan':
        AuthController::requireRole('petugas');
        require __DIR__ . '/views/petugas/persetujuan.php';
        break;
    case 'petugas-pengembalian':
        AuthController::requireRole('petugas');
        require __DIR__ . '/views/petugas/pengembalian.php';
        break;
    case 'petugas-pengembalian-data':
        AuthController::requireRole('petugas');
        require __DIR__ . '/views/petugas/pengembalian-data.php';
        break;
    case 'petugas-profil':
        AuthController::requireRole('petugas');
        require __DIR__ . '/views/petugas/profil.php';
        break;
    case 'petugas-laporan-pdf':
        AuthController::requireRole('petugas');
        require __DIR__ . '/views/petugas/laporan-pdf.php';
        break;
    case 'peminjam-dashboard':
        AuthController::requireRole('peminjam');
        require __DIR__ . '/views/peminjam/dashboard.php';
        break;
    case 'peminjam-daftar-alat':
        AuthController::requireRole('peminjam');
        require __DIR__ . '/views/peminjam/daftar-alat.php';
        break;
    case 'peminjam-pengajuan':
        AuthController::requireRole('peminjam');
        require __DIR__ . '/views/peminjam/pengajuan.php';
        break;
    case 'peminjam-riwayat':
        AuthController::requireRole('peminjam');
        require __DIR__ . '/views/peminjam/riwayat-peminjaman.php';
        break;
    case 'peminjam-pengembalian':
        AuthController::requireRole('peminjam');
        require __DIR__ . '/views/peminjam/pengembalian.php';
        break;
    case 'peminjam-profil':
        AuthController::requireRole('peminjam');
        require __DIR__ . '/views/peminjam/profil.php';
        break;
    default:
        redirect('index.php?page=' . ($user['role'] === 'admin' ? 'admin-dashboard' : ($user['role'] === 'petugas' ? 'petugas-dashboard' : 'peminjam-dashboard')));
}

require_once __DIR__ . '/views/layouts/footer.php';
