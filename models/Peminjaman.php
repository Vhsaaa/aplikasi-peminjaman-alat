<?php
class Peminjaman
{
    public static function all()
    {
        global $conn;
        $result = $conn->query('SELECT p.*, u.nama_lengkap, a.nama_alat FROM peminjaman p JOIN users u ON u.id_user = p.id_user JOIN alat a ON a.id_alat = p.id_alat ORDER BY p.created_at DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function byUser($idUser)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT p.*, a.nama_alat, u.nama_lengkap FROM peminjaman p JOIN alat a ON a.id_alat = p.id_alat JOIN users u ON u.id_user = p.id_user WHERE p.id_user = ? ORDER BY p.created_at DESC');
        $stmt->bind_param('i', $idUser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function pending()
    {
        global $conn;
        $result = $conn->query('SELECT p.*, u.nama_lengkap, a.nama_alat FROM peminjaman p JOIN users u ON u.id_user = p.id_user JOIN alat a ON a.id_alat = p.id_alat WHERE p.status = "menunggu" ORDER BY p.created_at DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function approved()
    {
        global $conn;
        $result = $conn->query('SELECT p.*, u.nama_lengkap, a.nama_alat FROM peminjaman p JOIN users u ON u.id_user = p.id_user JOIN alat a ON a.id_alat = p.id_alat WHERE p.status = "disetujui" ORDER BY p.created_at DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function pendingCount()
    {
        global $conn;
        $result = $conn->query('SELECT COUNT(*) as total FROM peminjaman WHERE status = "menunggu"');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public static function approvedCount()
    {
        global $conn;
        $result = $conn->query('SELECT COUNT(*) as total FROM peminjaman WHERE status = "disetujui"');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public static function finishedCount()
    {
        global $conn;
        $result = $conn->query('SELECT COUNT(*) as total FROM peminjaman WHERE status = "selesai"');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public static function totalCount()
    {
        global $conn;
        $result = $conn->query('SELECT COUNT(*) as total FROM peminjaman');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public static function findById($id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT p.*, u.nama_lengkap, a.nama_alat, a.stok FROM peminjaman p JOIN users u ON u.id_user = p.id_user JOIN alat a ON a.id_alat = p.id_alat WHERE p.id_peminjaman = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function create($idUser, $idAlat, $jumlah, $tanggalPinjam, $tanggalRencanaKembali, $keterangan)
    {
        global $conn;
        $kode = 'PMJ-' . date('YmdHis') . '-' . rand(100, 999);
        $stmt = $conn->prepare('INSERT INTO peminjaman (kode_peminjaman, id_user, id_alat, jumlah, tanggal_pinjam, tanggal_rencana_kembali, status, disetujui_oleh, alasan_penolakan, created_at) VALUES (?, ?, ?, ?, ?, ?, "menunggu", NULL, NULL, NOW())');
        $stmt->bind_param('siiiss', $kode, $idUser, $idAlat, $jumlah, $tanggalPinjam, $tanggalRencanaKembali);
        return $stmt->execute();
    }

    public static function approve($idPeminjaman, $idPetugas)
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE peminjaman SET status = "disetujui", disetujui_oleh = ?, alasan_penolakan = NULL WHERE id_peminjaman = ?');
        $stmt->bind_param('ii', $idPetugas, $idPeminjaman);
        return $stmt->execute();
    }

    public static function reject($idPeminjaman, $alasan)
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE peminjaman SET status = "ditolak", alasan_penolakan = ? WHERE id_peminjaman = ?');
        $stmt->bind_param('si', $alasan, $idPeminjaman);
        return $stmt->execute();
    }

    public static function complete($idPeminjaman)
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE peminjaman SET status = "selesai" WHERE id_peminjaman = ?');
        $stmt->bind_param('i', $idPeminjaman);
        return $stmt->execute();
    }
}
