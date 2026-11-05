<?php
$sessionUser = $_SESSION['user'];
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-link d-md-none text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <span class="navbar-brand mb-0 h5 text-primary fw-bold">APLIKASI PEMINJAMAN ALAT</span>
        <div class="ms-auto d-flex align-items-center gap-3">
            <div class="text-end">
                <div class="fw-bold small text-dark"><?= e($sessionUser['nama_lengkap']) ?></div>
                <div class="text-muted small text-uppercase"><?= e($sessionUser['role']) ?></div>
            </div>
            <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>
</nav>
