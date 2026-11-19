# Aplikasi Peminjaman Alat — PHP Native + MySQL

## Kebutuhan
- Laragon (Apache + MySQL)
- PHP 8.0+ (disarankan 8.1+)
- Browser modern

## Instalasi di Laragon
1. Ekstrak folder `peminjaman-alat` ke `C:\laragon\www\`.
2. Jalankan Laragon, klik **Start All**.
3. Buka `http://localhost/phpmyadmin`.
4. Import file `database/peminjaman_alat.sql`.
5. Periksa `config/database.php`. Default Laragon: host `127.0.0.1`, database `peminjaman_alat`, user `root`, password kosong.
6. Buka `http://localhost/peminjaman-alat/`.

## Akun demo
Semua akun demo menggunakan password `password`:
- Admin: `admin`
- Petugas: `petugas`
- Peminjam: `peminjam`

Ganti password demo dan hapus/nonaktifkan akun demo sebelum aplikasi digunakan sungguhan.

## Alur penggunaan
1. Admin membuat akun, kategori, dan data alat.
2. Peminjam melihat daftar alat lalu mengajukan peminjaman.
3. Petugas menyetujui atau menolak pengajuan.
4. Saat disetujui, stok tersedia dikurangi.
5. Peminjam mengajukan pengembalian setelah menyerahkan alat secara fisik.
6. Petugas memverifikasi kondisi alat; stok dikembalikan dan transaksi menjadi selesai.
7. Petugas memilih rentang tanggal untuk laporan dan mencetaknya.

## Hak akses sesuai lembar tugas
- Admin: Login, Logout, CRUD user, CRUD alat, CRUD kategori, melihat/mengelola data peminjaman, melihat data pengembalian, log aktivitas.
- Petugas: Login, Logout, menyetujui peminjaman, memantau dan memverifikasi pengembalian, mencetak laporan.
- Peminjam: Login, Logout, melihat daftar alat, mengajukan peminjaman, mengajukan pengembalian.

## Catatan implementasi
- Password disimpan menggunakan `password_hash()` dan diverifikasi dengan `password_verify()`.
- Query memakai PDO prepared statements.
- Form POST dilindungi token CSRF.
- Akses halaman dibatasi berdasarkan role.
- Proses persetujuan dan verifikasi pengembalian menggunakan transaksi database.
- Untuk produksi, tambahkan HTTPS, backup rutin, kebijakan pergantian password, validasi tanggal di server yang lebih ketat, serta audit keamanan.
