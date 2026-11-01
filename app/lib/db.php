<?php
/**
 * app/lib/db.php — Koneksi Database PDO Fail-Safe & Graceful Degradation.
 *
 * Mengambil kredensial secara murni dari variabel lingkungan (.env).
 * Jika database mati/terputus, fungsi ini tidak melempar Fatal Error,
 * melainkan mengembalikan null dan mencatat log, sehingga halaman profil
 * tetap dapat tampil dengan normal.
 */

require_once __DIR__ . '/env.php';

function db(): ?PDO
{
    static $pdo = null;
    static $attempted = false;

    if ($attempted) {
        return $pdo;
    }
    $attempted = true;

    // Baca kredensial murni dari fungsi env()
    $host = env('DB_HOST', '127.0.0.1');
    $port = (int)env('DB_PORT', 3306);
    $name = env('DB_NAME', 'kelompok_db');
    $user = env('DB_USER', 'app_local');
    $pass = env('DB_PASS', 'password_lokal');

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 2, // Timeout cepat agar web tidak lambat jika remote DB mati
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('[DB] Gagal terkoneksi ke database: ' . $e->getMessage());
        $pdo = null;
        return null;
    }
}
