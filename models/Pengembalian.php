<?php
class Pengembalian
{
    public static function all()
    {
        global $conn;
        $result = $conn->query('SELECT pg.*, p.kode_peminjaman, u.nama_lengkap, a.nama_alat FROM pengembalian pg JOIN peminjaman p ON p.id_peminjaman = pg.id_peminjaman JOIN users u ON u.id_user = p.id_user JOIN alat a ON a.id_alat = p.id_alat ORDER BY pg.id_pengembalian DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findByPeminjaman($idPeminjaman)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM pengembalian WHERE id_peminjaman = ? LIMIT 1');
        $stmt->bind_param('i', $idPeminjaman);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function create($idPeminjaman, $tanggalReal, $keterlambatan, $denda, $keterangan)
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO pengembalian (id_peminjaman, tanggal_kembali_real, keterlambatan, denda, keterangan) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('isdis', $idPeminjaman, $tanggalReal, $keterlambatan, $denda, $keterangan);
        return $stmt->execute();
    }

    public static function totalDenda()
    {
        global $conn;
        $result = $conn->query('SELECT COALESCE(SUM(denda),0) as total FROM pengembalian');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }
}
