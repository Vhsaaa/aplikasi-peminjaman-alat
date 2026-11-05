<<<<<<< HEAD
# Aplikasi Peminjaman Alat

## Deskripsi Aplikasi
Sistem informasi berbasis web untuk mengelola proses peminjaman dan pengembalian alat secara digital. Aplikasi ini dibuat untuk kebutuhan UKK RPL Tahun Pelajaran 2026/2027 dan mendukung 3 role user: Admin, Petugas, dan Peminjam.

## Fitur Aplikasi
- Login 3 role dengan PHP Session
- CRUD User, Alat, dan Kategori
- Pengajuan dan persetujuan peminjaman
- Proses pengembalian dan perhitungan denda otomatis
- Log aktivitas pengguna
- Laporan dan cetak data
- Dashboard sesuai role
- Responsive design dengan Bootstrap 5

## Teknologi yang Digunakan
- PHP 8 Native
- MySQL
- HTML5, CSS3, JavaScript
- Bootstrap 5
- Font Awesome
- SweetAlert2
- XAMPP

## Persyaratan Instalasi
- XAMPP dengan PHP 8 dan MySQL aktif
- Browser modern
- Text editor seperti VS Code

## Cara Menjalankan Aplikasi
1. Letakkan folder project di direktori `htdocs` atau `www` sesuai XAMPP.
2. Jalankan Apache dan MySQL di XAMPP.
3. Import file `database/db_peminjaman.sql` ke phpMyAdmin atau MySQL.
4. Buka browser dan akses `http://localhost/peminjaman-alat/index.php`.

## Cara Import Database
1. Buka phpMyAdmin.
2. Buat database dengan nama `db_peminjaman`.
3. Import file `database/db_peminjaman.sql`.

## Konfigurasi Koneksi Database
File koneksi berada di `config/database.php`.

```php
<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_peminjaman';
```

## Akun Demo
- Admin
  - Username: `admin`
  - Password: `admin123`
- Petugas
  - Username: `petugas`
  - Password: `petugas123`
- Peminjam
  - Username: `peminjam`
  - Password: `peminjam123`

## Screenshot Aplikasi
Belum tersedia. Anda dapat menambahkan screenshot pada folder `docs` dan menghubungkan ke README setelah pengambilan gambar.

## Git Commands
```bash
git init
git add .
git commit -m "feat: Selesaikan Aplikasi Peminjaman Alat UKK RPL"
git branch -M main
git remote add origin https://github.com/USERNAME-ANDA/aplikasi-peminjaman.git
git push -u origin main
```
=======
# aplikasi-peminjaman-alat
>>>>>>> 96e291f5e2181ebd41ba097cf8aed87051e1bccb
