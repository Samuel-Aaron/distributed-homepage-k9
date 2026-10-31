<?php
/**
 * app/lib/helper.php — Fungsi utilitas keamanan dan formatting.
 */

// Sanitasi output HTML untuk mencegah XSS
function e(?string $str): string
{
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Format angka Indonesia
function fmt_angka(?int $n): string
{
    return $n === null ? '–' : number_format($n, 0, ',', '.');
}

// Inisialisasi token CSRF
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verifikasi token CSRF
function csrf_verify(?string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Dapatkan hash IP untuk privasi
function get_ip_hash(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    return hash('sha256', $ip . 'komputasi_awan_salt');
}
