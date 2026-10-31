<?php
/**
 * app/lib/db.php — Koneksi Database PDO Fail-Safe & Graceful Degradation.
 *
 * Membaca kredensial dari ../config/db.php.
 * Jika database mati/terputus, fungsi ini tidak melempar Fatal Error,
 * melainkan mengembalikan null dan mencatat log, sehingga halaman profil
 * tetap dapat tampil dengan normal.
 */

function db(): ?PDO
{
    static $pdo = null;
    static $attempted = false;

    if ($attempted) {
        return $pdo;
    }
    $attempted = true;

    $configPath = dirname(__DIR__, 2) . '/config/db.php';
    if (!file_exists($configPath)) {
        error_log('[DB] File konfigurasi config/db.php tidak ditemukan.');
        return null;
    }

    $c = require $configPath;
    if (!is_array($c)) {
        error_log('[DB] Format config/db.php tidak valid.');
        return null;
    }

    $host = $c['host'] ?? '127.0.0.1';
    $name = $c['name'] ?? 'kelompok_db';
    $user = $c['user'] ?? 'app_local';
    $pass = $c['pass'] ?? '';
    $port = $c['port'] ?? 3306;

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
