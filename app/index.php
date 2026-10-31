<?php
/**
 * app/index.php — Halaman Dashboard Utama Aplikasi Bersama (Penghitung Kunjungan)
 */

$identityPath = dirname(__DIR__) . '/identity.php';
if (file_exists($identityPath)) {
    require_once $identityPath;
}

require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/helper.php';

$pdo = db();
$dbConnected = ($pdo !== null);

// Inisialisasi awal log kunjungan jika ada tabel
$stats = [
    'total' => 0,
    'today' => 0,
    'failover_count' => 0,
];

if ($dbConnected) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) AS total FROM kunjungan");
        $stats['total'] = (int)$stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) AS today FROM kunjungan WHERE DATE(created_at) = CURRENT_DATE");
        $stats['today'] = (int)$stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) AS failover FROM kunjungan WHERE is_failover = 1");
        $stats['failover_count'] = (int)$stmt->fetchColumn();
    } catch (Throwable $e) {
        error_log('[Dashboard] Gagal memuat statistik: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kunjungan Terdistribusi — Kelompok</title>
    <link rel="stylesheet" href="assets/app.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        .header {
            background: #ffffff;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }
        .db-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-ok { background: #dcfce7; color: #166534; }
        .status-err { background: #fee2e2; color: #991b1b; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .card {
            background: #ffffff;
            padding: 1.25rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .card h3 {
            margin: 0 0 0.5rem 0;
            font-size: 0.875rem;
            color: #64748b;
        }
        .card p {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
        }
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
            color: #2563eb;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <?php 
    $activeMember = 'app';
    include __DIR__ . '/partials/served_by.php'; 
    ?>

    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0 0 0.5rem 0; font-size: 1.5rem;">📊 Dashboard Penghitung Kunjungan Terdistribusi</h1>
                    <p style="margin: 0; color: #64748b; font-size: 0.9rem;">
                        Aplikasi bersama untuk monitoring distribusi beban server dan failover.
                    </p>
                </div>
                <div>
                    <?php if ($dbConnected): ?>
                        <span class="db-status status-ok">● Database Terhubung</span>
                    <?php else: ?>
                        <span class="db-status status-err">○ Database Terputus (Graceful Fallback)</span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="../" class="back-link">← Kembali ke Halaman Personal</a>
        </div>

        <div class="stats-grid">
            <div class="card">
                <h3>Total Kunjungan</h3>
                <p><?= fmt_angka($stats['total']) ?></p>
            </div>
            <div class="card">
                <h3>Kunjungan Hari Ini</h3>
                <p><?= fmt_angka($stats['today']) ?></p>
            </div>
            <div class="card">
                <h3>Penyelamatan Failover</h3>
                <p><?= fmt_angka($stats['failover_count']) ?></p>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1rem; color: #0f172a; margin-bottom: 0.75rem;">Status Pengerjaan Modul Bersama</h3>
            <p style="font-size: 0.9rem; font-weight: normal; color: #475569; line-height: 1.6;">
                Kerangka aplikasi telah siap. Modul Form Check-in, Paginasi Riwayat, dan Visualisasi Grafik akan dikembangkan pada task berikutnya melalui Pull Request.
            </p>
        </div>
    </div>
</body>
</html>
