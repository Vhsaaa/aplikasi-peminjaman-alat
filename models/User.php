<?php
class User
{
    public static function all()
    {
        global $conn;
        $result = $conn->query('SELECT * FROM users ORDER BY created_at DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findById($id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM users WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function findByUsername($username)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function count()
    {
        global $conn;
        $result = $conn->query('SELECT COUNT(*) as total FROM users');
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public static function create($nama_lengkap, $username, $password, $role)
    {
        global $conn;
        if (self::findByUsername($username)) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (nama_lengkap, username, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->bind_param('ssss', $nama_lengkap, $username, $hash, $role);
        return $stmt->execute();
    }

    public static function update($id, $nama_lengkap, $username, $role, $password = null)
    {
        global $conn;
        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE users SET nama_lengkap = ?, username = ?, role = ?, password = ? WHERE id_user = ?');
            $stmt->bind_param('ssssi', $nama_lengkap, $username, $role, $hash, $id);
        } else {
            $stmt = $conn->prepare('UPDATE users SET nama_lengkap = ?, username = ?, role = ? WHERE id_user = ?');
            $stmt->bind_param('sssi', $nama_lengkap, $username, $role, $id);
        }
        return $stmt->execute();
    }

    public static function delete($id)
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM users WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
