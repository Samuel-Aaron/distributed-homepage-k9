<?php
/**
 * index.php — Gateway & Failover Router Personal Page.
 *
 * Alur Kerja:
 * 1. Membaca identitas server secara murni dari .env (MEMBER_ROUTE, MEMBER_NAMA, MEMBER_NIM).
 * 2. Menentukan profil anggota mana yang harus ditampilkan:
 *    - Default: menampilkan profil milik server ini (misal m0).
 *    - Failover: jika ada parameter ?member=m[x], menampilkan profil anggota tersebut.
 * 3. Banner 'served_by.php' akan secara otomatis mendeteksi apakah request
 *    dilayani secara langsung atau dilayani oleh server cadangan (failover).
 */

// 1. Muat loader konfigurasi .env
require_once __DIR__ . '/app/lib/env.php';

// 2. Tetapkan identitas server fisik dari .env
if (!defined('MEMBER_ROUTE')) {
    define('MEMBER_ROUTE', env('MEMBER_ROUTE', 'm0'));
    define('MEMBER_NAMA',  env('MEMBER_NAMA', 'Samuel Aaron'));
    define('MEMBER_NIM',   env('MEMBER_NIM', '103012580024'));
}

// 3. Tentukan target member yang ingin diakses
$defaultRoute = defined('MEMBER_ROUTE') ? MEMBER_ROUTE : 'm0';
$targetMember = $_GET['member'] ?? $defaultRoute;

// Validasi route hanya boleh m0..m3
$validRoutes = ['m0', 'm1', 'm2', 'm3'];
if (!in_array($targetMember, $validRoutes, true)) {
    $targetMember = $defaultRoute;
}

// Variabel untuk dibaca oleh partials/served_by.php dan partials/node_nav.php
$activeMember = $targetMember;

// 4. Muat file view profil anggota
$memberView = __DIR__ . "/members/{$targetMember}/view.php";

if (file_exists($memberView)) {
    require $memberView;
} else {
    // Fallback darurat jika file anggota belum di-push
    http_response_code(404);
    echo "<h1>Halaman untuk route " . htmlspecialchars($targetMember) . " belum tersedia.</h1>";
    echo "<p><a href='?member={$defaultRoute}'>Kembali ke Halaman Utama Server</a></p>";
}
