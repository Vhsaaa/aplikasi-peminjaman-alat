<?php
class AuthController
{
    public static function login()
    {
        global $conn;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            flash('error', 'Username dan password wajib diisi.');
            redirect('index.php?page=login');
        }

        $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Username/Password Salah');
            redirect('index.php?page=login');
        }

        $_SESSION['user'] = [
            'id_user' => $user['id_user'],
            'nama_lengkap' => $user['nama_lengkap'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];

        self::logActivity($user['id_user'], 'Login', 'User berhasil login ke sistem.');

        $role = $user['role'];
        $redirectMap = [
            'admin' => 'index.php?page=admin-dashboard',
            'petugas' => 'index.php?page=petugas-dashboard',
            'peminjam' => 'index.php?page=peminjam-dashboard',
        ];

        redirect($redirectMap[$role] ?? 'index.php?page=login');
    }

    public static function logout()
    {
        if (!empty($_SESSION['user'])) {
            self::logActivity($_SESSION['user']['id_user'], 'Logout', 'User logout dari sistem.');
        }

        session_unset();
        session_destroy();
        redirect('index.php?page=login');
    }

    public static function requireAuth()
    {
        if (!isset($_SESSION['user'])) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('index.php?page=login');
        }
    }

    public static function requireRole($roles)
    {
        self::requireAuth();
        $user = $_SESSION['user'];
        if (!in_array($user['role'], (array) $roles, true)) {
            flash('error', 'Anda tidak memiliki akses ke halaman ini.');
            if ($user['role'] === 'admin') {
                redirect('index.php?page=admin-dashboard');
            }
            if ($user['role'] === 'petugas') {
                redirect('index.php?page=petugas-dashboard');
            }
            redirect('index.php?page=peminjam-dashboard');
        }
    }

    public static function logActivity($idUser, $aktivitas, $keterangan)
    {
        global $conn;

        $stmt = $conn->prepare('INSERT INTO log_aktivitas (id_user, aktivitas, keterangan, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('iss', $idUser, $aktivitas, $keterangan);
        $stmt->execute();
    }
}
