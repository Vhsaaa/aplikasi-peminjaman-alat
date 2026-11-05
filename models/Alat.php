<?php
class Alat
{
    public static function all()
    {
        global $conn;
        $result = $conn->query('SELECT a.*, k.nama_kategori FROM alat a JOIN kategori k ON k.id_kategori = a.id_kategori ORDER BY a.id_alat DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function allWithCategoryFilter($kategoriId = null)
    {
        global $conn;
        $sql = 'SELECT a.*, k.nama_kategori FROM alat a JOIN kategori k ON k.id_kategori = a.id_kategori';
        $params = [];
        $types = '';

        if ($kategoriId !== null && $kategoriId !== '') {
            $sql .= ' WHERE a.id_kategori = ?';
            $params[] = $kategoriId;
            $types .= 'i';
        }

        $sql .= ' ORDER BY a.id_alat DESC';
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findById($id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT a.*, k.nama_kategori FROM alat a JOIN kategori k ON k.id_kategori = a.id_kategori WHERE a.id_alat = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function count()
    {
        global $conn;
        $result = $conn->query('SELECT COUNT(*) as total FROM alat');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public static function create($id_kategori, $nama_alat, $deskripsi, $stok, $kondisi, $gambar)
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO alat (id_kategori, nama_alat, deskripsi, stok, kondisi, gambar) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ississ', $id_kategori, $nama_alat, $deskripsi, $stok, $kondisi, $gambar);
        return $stmt->execute();
    }

    public static function update($id, $id_kategori, $nama_alat, $deskripsi, $stok, $kondisi, $gambar = null)
    {
        global $conn;
        if ($gambar !== null && $gambar !== '') {
            $stmt = $conn->prepare('UPDATE alat SET id_kategori = ?, nama_alat = ?, deskripsi = ?, stok = ?, kondisi = ?, gambar = ? WHERE id_alat = ?');
            $stmt->bind_param('ississi', $id_kategori, $nama_alat, $deskripsi, $stok, $kondisi, $gambar, $id);
        } else {
            $stmt = $conn->prepare('UPDATE alat SET id_kategori = ?, nama_alat = ?, deskripsi = ?, stok = ?, kondisi = ? WHERE id_alat = ?');
            $stmt->bind_param('issisi', $id_kategori, $nama_alat, $deskripsi, $stok, $kondisi, $id);
        }
        return $stmt->execute();
    }

    public static function delete($id)
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM alat WHERE id_alat = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function getAvailable($id = null)
    {
        global $conn;
        $sql = 'SELECT a.*, k.nama_kategori FROM alat a JOIN kategori k ON k.id_kategori = a.id_kategori WHERE a.stok > 0';
        if ($id !== null) {
            $sql .= ' AND a.id_alat = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
        } else {
            $stmt = $conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
