<?php
$role = $_SESSION['user']['role'];
$items = [
    'admin' => [
        ['Dashboard', 'admin-dashboard', 'fa-gauge'],
        ['Manajemen User', 'admin-user', 'fa-users'],
        ['Manajemen Alat', 'admin-alat', 'fa-toolbox'],
        ['Manajemen Kategori', 'admin-kategori', 'fa-tags'],
        ['Data Peminjaman', 'admin-peminjaman', 'fa-clipboard-list'],
        ['Data Pengembalian', 'admin-pengembalian', 'fa-arrow-rotate-left'],
        ['Log Aktivitas', 'admin-log', 'fa-clipboard'],
        ['Profil', 'admin-profil', 'fa-user'],
    ],
    'petugas' => [
        ['Dashboard', 'petugas-dashboard', 'fa-gauge'],
        ['Persetujuan', 'petugas-persetujuan', 'fa-check-circle'],
        ['Pengembalian', 'petugas-pengembalian', 'fa-rotate-left'],
        ['Data Peminjaman', 'petugas-peminjaman', 'fa-book'],
        ['Data Pengembalian', 'petugas-pengembalian-data', 'fa-boxes-stacked'],
        ['Laporan', 'petugas-laporan', 'fa-file-lines'],
        ['Laporan PDF', 'petugas-laporan-pdf', 'fa-file-pdf'],
        ['Profil', 'petugas-profil', 'fa-user'],
    ],
    'peminjam' => [
        ['Dashboard', 'peminjam-dashboard', 'fa-gauge'],
        ['Daftar Alat', 'peminjam-daftar-alat', 'fa-list'],
        ['Ajukan Peminjaman', 'peminjam-pengajuan', 'fa-hand-holding-hand'],
        ['Riwayat', 'peminjam-riwayat', 'fa-clock-rotate-left'],
        ['Pengembalian', 'peminjam-pengembalian', 'fa-rotate-left'],
        ['Profil', 'peminjam-profil', 'fa-user'],
    ]
];
$current = $_GET['page'] ?? '';
?>
<div class="sidebar-wrapper">
    <div class="sidebar" id="sidebarMenu">
        <div class="brand-box text-center py-4">
            <div class="brand-icon mb-2"><i class="fa-solid fa-screwdriver-wrench"></i></div>
            <div class="brand-title">Peminjaman</div>
        </div>
        <ul class="nav flex-column px-2">
            <?php foreach ($items[$role] as $item): ?>
                <?php $pageName = $item[1]; ?>
                <li class="nav-item">
                    <a class="nav-link <?= $current === $pageName ? 'active' : '' ?>" href="index.php?page=<?= $pageName ?>">
                        <i class="fa-solid <?= $item[2] ?>"></i>
                        <span><?= e($item[0]) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
