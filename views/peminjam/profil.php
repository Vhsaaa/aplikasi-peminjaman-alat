<?php
AuthController::requireRole('peminjam');
$user = $_SESSION['user'];
?>
<div class="main-content">
    <div class="page-container">
        <div class="card-box p-4">
            <h3 class="fw-bold mb-3">Profil Peminjam</h3>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nama</label><input type="text" class="form-control" value="<?= e($user['nama_lengkap']) ?>" readonly></div>
                <div class="col-md-6"><label class="form-label">Username</label><input type="text" class="form-control" value="<?= e($user['username']) ?>" readonly></div>
                <div class="col-md-6"><label class="form-label">Role</label><input type="text" class="form-control" value="<?= e($user['role']) ?>" readonly></div>
            </div>
        </div>
    </div>
</div>
