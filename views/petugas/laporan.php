<?php
AuthController::requireRole('petugas');

$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');

$allData = Peminjaman::all();
$logs = [];

foreach ($allData as $item) {
    $match = true;

    if ($startDate && $item['tanggal_pinjam'] < $startDate) {
        $match = false;
    }
    if ($endDate && $item['tanggal_pinjam'] > $endDate) {
        $match = false;
    }
    if ($statusFilter && $item['status'] !== $statusFilter) {
        $match = false;
    }
    if ($search !== '') {
        $haystack = strtolower($item['kode_peminjaman'] . ' ' . $item['nama_lengkap'] . ' ' . $item['nama_alat']);
        if (strpos($haystack, strtolower($search)) === false) {
            $match = false;
        }
    }

    if ($match) {
        $logs[] = $item;
    }
}

$totalDenda = 0;
foreach ($logs as $item) {
    $totalDenda += (float) ($item['denda'] ?? 0);
}
?>
<div class="main-content">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Laporan</h3>
                <p class="text-muted mb-0">Data peminjaman dan pengembalian</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </button>
                <a class="btn btn-outline-primary" href="index.php?page=petugas-laporan-pdf&start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>&status=<?= urlencode($statusFilter) ?>&search=<?= urlencode($search) ?>" target="_blank">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card-box p-4 mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="petugas-laporan">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="<?= e($startDate) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="<?= e($endDate) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu" <?= $statusFilter === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="disetujui" <?= $statusFilter === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="ditolak" <?= $statusFilter === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        <option value="selesai" <?= $statusFilter === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" value="<?= e($search) ?>" placeholder="Nama / kode / alat">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="card-box p-4">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="fw-bold mb-0">Rekap</h5>
                <span class="badge bg-primary">Total: <?= count($logs) ?> transaksi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Peminjaman</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Alat</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach ($logs as $item): ?>
                        <tr>
                            <td><?= e($no++) ?></td>
                            <td><?= e($item['kode_peminjaman']) ?></td>
                            <td><?= e($item['nama_lengkap']) ?></td>
                            <td><?= e($item['nama_alat']) ?></td>
                            <td><?= e($item['jumlah']) ?></td>
                            <td><?= e($item['tanggal_pinjam']) ?></td>
                            <td><?= e($item['tanggal_rencana_kembali']) ?></td>
                            <td><?= e($item['status']) ?></td>
                            <td>Rp<?= number_format($item['denda'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">Tidak ada data laporan sesuai filter.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-end fw-bold">
                Total Denda: Rp<?= number_format($totalDenda, 0, ',', '.') ?>
            </div>
        </div>
    </div>
</div>
