<?php
class LogAktivitas
{
    public static function all()
    {
        global $conn;
        $result = $conn->query('SELECT l.*, u.nama_lengkap FROM log_aktivitas l JOIN users u ON u.id_user = l.id_user ORDER BY l.created_at DESC LIMIT 20');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
