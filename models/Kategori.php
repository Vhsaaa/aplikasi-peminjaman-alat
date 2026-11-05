<?php
class Kategori
{
    public static function all()
    {
        global $conn;
        $result = $conn->query('SELECT * FROM kategori ORDER BY id_kategori ASC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findById($id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM kategori WHERE id_kategori = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function count()
    {
        global $conn;
        $result = $conn->query('SELECT COUNT(*) as total FROM kategori');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public static function create($nama_kategori, $deskripsi)
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)');
        $stmt->bind_param('ss', $nama_kategori, $deskripsi);
        return $stmt->execute();
    }

    public static function update($id, $nama_kategori, $deskripsi)
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE id_kategori = ?');
        $stmt->bind_param('ssi', $nama_kategori, $deskripsi, $id);
        return $stmt->execute();
    }

    public static function delete($id)
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM kategori WHERE id_kategori = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
