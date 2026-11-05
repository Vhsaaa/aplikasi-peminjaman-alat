CREATE DATABASE IF NOT EXISTS db_peminjaman;
USE db_peminjaman;

DROP TABLE IF EXISTS log_aktivitas;
DROP TABLE IF EXISTS pengembalian;
DROP TABLE IF EXISTS peminjaman;
DROP TABLE IF EXISTS alat;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','petugas','peminjam') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_users_role (role),
    INDEX idx_users_username (username)
);

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    INDEX idx_kategori_nama (nama_kategori)
);

CREATE TABLE alat (
    id_alat INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    nama_alat VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    stok INT NOT NULL DEFAULT 0,
    kondisi ENUM('Baik','Rusak','Perlu Servis') NOT NULL DEFAULT 'Baik',
    gambar VARCHAR(255) DEFAULT 'default.jpg',
    CONSTRAINT fk_alat_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_alat_kategori (id_kategori),
    INDEX idx_alat_nama (nama_alat),
    INDEX idx_alat_stok (stok)
);

CREATE TABLE peminjaman (
    id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
    kode_peminjaman VARCHAR(50) NOT NULL UNIQUE,
    id_user INT NOT NULL,
    id_alat INT NOT NULL,
    jumlah INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_rencana_kembali DATE NOT NULL,
    status ENUM('menunggu','disetujui','ditolak','selesai') NOT NULL DEFAULT 'menunggu',
    disetujui_oleh INT DEFAULT NULL,
    alasan_penolakan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_peminjaman_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_peminjaman_alat FOREIGN KEY (id_alat) REFERENCES alat(id_alat) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_peminjaman_petugas FOREIGN KEY (disetujui_oleh) REFERENCES users(id_user) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_peminjaman_status (status),
    INDEX idx_peminjaman_user (id_user),
    INDEX idx_peminjaman_alat (id_alat)
);

CREATE TABLE pengembalian (
    id_pengembalian INT AUTO_INCREMENT PRIMARY KEY,
    id_peminjaman INT NOT NULL,
    tanggal_kembali_real DATE NOT NULL,
    keterlambatan INT NOT NULL DEFAULT 0,
    denda DECIMAL(12,2) NOT NULL DEFAULT 0,
    keterangan TEXT,
    CONSTRAINT fk_pengembalian_peminjaman FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id_peminjaman) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE (id_peminjaman),
    INDEX idx_pengembalian_tanggal (tanggal_kembali_real)
);

CREATE TABLE log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    aktivitas VARCHAR(100) NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_log_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_log_user (id_user),
    INDEX idx_log_aktivitas (aktivitas)
);

DELIMITER $$
CREATE FUNCTION fn_hitung_denda(tanggal_rencana DATE, tanggal_real DATE)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE selisih INT;
    SET selisih = DATEDIFF(tanggal_real, tanggal_rencana);
    IF selisih <= 0 THEN
        RETURN 0;
    END IF;
    RETURN selisih * 5000;
END$$

CREATE TRIGGER trg_kurangi_stok_setujui
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = 'disetujui' AND OLD.status <> 'disetujui' THEN
        UPDATE alat
        SET stok = stok - NEW.jumlah
        WHERE id_alat = NEW.id_alat AND stok >= NEW.jumlah;
    END IF;
END$$

CREATE TRIGGER trg_tambah_stok_pengembalian
AFTER INSERT ON pengembalian
FOR EACH ROW
BEGIN
    UPDATE peminjaman
    SET status = 'selesai'
    WHERE id_peminjaman = NEW.id_peminjaman AND status <> 'selesai';

    UPDATE alat a
    JOIN peminjaman p ON p.id_alat = a.id_alat
    SET a.stok = a.stok + p.jumlah
    WHERE p.id_peminjaman = NEW.id_peminjaman;
END$$

CREATE PROCEDURE sp_pinjam_alat(
    IN p_id_user INT,
    IN p_id_alat INT,
    IN p_jumlah INT,
    IN p_tanggal_pinjam DATE,
    IN p_tanggal_rencana_kembali DATE,
    IN p_keterangan TEXT
)
BEGIN
    DECLARE v_stok INT;
    DECLARE v_status INT DEFAULT 0;

    START TRANSACTION;

    SELECT stok INTO v_stok FROM alat WHERE id_alat = p_id_alat FOR UPDATE;

    IF v_stok IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Alat tidak ditemukan';
    ELSEIF p_jumlah < 1 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jumlah pinjam minimal 1';
    ELSEIF p_jumlah > v_stok THEN
        ROLLBACK;
        SELECT 'Stok Tidak Cukup' AS pesan;
    ELSE
        INSERT INTO peminjaman (
            kode_peminjaman, id_user, id_alat, jumlah, tanggal_pinjam, tanggal_rencana_kembali, status, created_at
        ) VALUES (
            CONCAT('PMJ-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', FLOOR(RAND()*900+100)),
            p_id_user,
            p_id_alat,
            p_jumlah,
            p_tanggal_pinjam,
            p_tanggal_rencana_kembali,
            'menunggu',
            NOW()
        );
        COMMIT;
        SELECT 'Pengajuan peminjaman berhasil dikirim' AS pesan;
    END IF;
END$$
DELIMITER ;

INSERT INTO users (nama_lengkap, username, password, role) VALUES
('Admin Utama', 'admin', '$2y$10$a7taxSFr8JkccxRa28C88O.L4HY75UMzVq1wAN2UKu/fXPT.c8uAW', 'admin'),
('Petugas Gudang', 'petugas', '$2y$10$j6ej3DgkMJkO8Sx2QuXtveCNmRpcPqohkfEVLQ1HHCY1pfqQaVMNe', 'petugas'),
('Peminjam Siswa', 'peminjam', '$2y$10$h2lzKmwx8pR4eY5ioL.CF.OHqfbKlFbZm3rQB84n9nEmFtRk0cAha', 'peminjam');

INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Elektronik', 'Peralatan elektronik yang digunakan untuk kegiatan pembelajaran atau kerja.'),
('Perbaikan', 'Peralatan pendukung dalam proses perbaikan dan maintenance.'),
('Kebersihan', 'Alat pendukung kebersihan dan penyediaan ruang kerja.');

INSERT INTO alat (id_kategori, nama_alat, deskripsi, stok, kondisi, gambar) VALUES
(1, 'Laptop', 'Laptop untuk kebutuhan kerja dan praktikum.', 5, 'Baik', 'laptop.jpg'),
(1, 'Proyektor', 'Proyektor untuk presentasi dan pembelajaran.', 3, 'Baik', 'proyektor.jpg'),
(2, 'Obeng Set', 'Set obeng untuk perbaikan ringan.', 8, 'Baik', 'obeng.jpg'),
(3, 'Pel Lantai', 'Alat pembersih lantai dan area kerja.', 10, 'Baik', 'pel.jpg');

INSERT INTO peminjaman (kode_peminjaman, id_user, id_alat, jumlah, tanggal_pinjam, tanggal_rencana_kembali, status, created_at) VALUES
('PMJ-20261105100001', 3, 1, 1, '2026-11-05', '2026-11-07', 'disetujui', NOW()),
('PMJ-20261105100002', 3, 2, 1, '2026-11-06', '2026-11-08', 'menunggu', NOW());

INSERT INTO pengembalian (id_peminjaman, tanggal_kembali_real, keterlambatan, denda, keterangan) VALUES
(1, '2026-11-09', 2, 10000, 'Terlambat 2 hari');

INSERT INTO log_aktivitas (id_user, aktivitas, keterangan, created_at) VALUES
(1, 'Login', 'Admin login ke sistem', NOW()),
(2, 'Login', 'Petugas login ke sistem', NOW()),
(3, 'Login', 'Peminjam login ke sistem', NOW());
