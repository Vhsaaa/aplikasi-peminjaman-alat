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
        $text = strtolower($item['kode_peminjaman'] . ' ' . $item['nama_lengkap'] . ' ' . $item['nama_alat']);
        if (strpos($text, strtolower($search)) === false) {
            $match = false;
        }
    }

    if ($match) {
        $logs[] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF - Petugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; margin: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .filter-box { margin-bottom: 16px; font-size: 12px; }
        .table-wrap { width: 100%; overflow-x: auto; }
        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td { border: 1px solid #333; padding: 8px; font-size: 12px; word-break: break-word; }
        th { background: #f2f2f2; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h4 class="mb-1">LAPORAN PEMINJAMAN ALAT</h4>
        <small>APLIKASI PEMINJAMAN ALAT</small>
    </div>

    <div class="mb-3 small">
        <strong>Filter:</strong>
        <?= e($startDate ?: '-') ?> s/d <?= e($endDate ?: '-') ?> |
        Status: <?= e($statusFilter ?: 'Semua') ?> |
        Pencarian: <?= e($search ?: '-') ?>
    </div>

    <div class="table-wrap">
        <table class="table table-sm">
            <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Pinjam</th>
                <th>Rencana Kembali</th>
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
                    <td colspan="9" class="text-center">Tidak ada data sesuai filter.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3 no-print">
        <button class="btn btn-primary" onclick="window.print()">Print / Simpan PDF</button>
    </div>
</body>
</html>
