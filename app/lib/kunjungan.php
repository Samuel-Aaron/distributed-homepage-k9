<?php
/**
 * app/lib/kunjungan.php — Logika pencatatan kunjungan (Task T-02).
 *
 * Setiap kali halaman dibuka, satu baris disimpan ke tabel `kunjungan`:
 *   - route_id       : server fisik yang melayani request (MEMBER_ROUTE dari .env)
 *   - target_member  : profil/halaman yang dilihat (m0..m3, atau 'app' untuk dashboard)
 *   - is_failover    : 1 jika server ini melayani profil milik anggota lain
 *   - ip_hash        : SHA-256 dari IP (IP asli tidak pernah disimpan)
 *
 * Fail-safe: jika database mati, fungsi mengembalikan false tanpa melempar error,
 * sehingga halaman tetap tampil normal.
 */

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helper.php';

/**
 * Catat satu kunjungan otomatis (jenis 'auto').
 *
 * @param PDO|null $pdo           Koneksi dari db(); null = database mati.
 * @param string   $targetMember  Halaman yang dilihat: m0..m3 atau 'app'.
 * @return bool                   true jika tercatat, false jika dilewati/gagal.
 */
function catat_kunjungan(?PDO $pdo, string $targetMember): bool
{
    // Hanya catat pemuatan halaman biasa, bukan HEAD/POST/dll.
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
        return false;
    }

    // Database mati: lewati tanpa error (graceful degradation).
    if ($pdo === null) {
        return false;
    }

    // Cegah pencatatan ganda dalam satu request.
    static $sudahDicatat = false;
    if ($sudahDicatat) {
        return false;
    }

    $serverRoute = defined('MEMBER_ROUTE') ? MEMBER_ROUTE : (string) env('MEMBER_ROUTE', 'm0');
    $isFailover  = preg_match('/^m[0-3]$/', $targetMember) === 1 && $targetMember !== $serverRoute;
    $userAgent   = mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);

    try {
        $stmt = $pdo->prepare(
            'INSERT INTO kunjungan (route_id, target_member, is_failover, jenis, ip_hash, user_agent)
             VALUES (:route_id, :target_member, :is_failover, :jenis, :ip_hash, :user_agent)'
        );
        $stmt->execute([
            ':route_id'      => $serverRoute,
            ':target_member' => $targetMember,
            ':is_failover'   => $isFailover ? 1 : 0,
            ':jenis'         => 'auto',
            ':ip_hash'       => get_ip_hash(),
            ':user_agent'    => $userAgent,
        ]);
        $sudahDicatat = true;
        return true;
    } catch (Throwable $e) {
        error_log('[Kunjungan] Gagal mencatat kunjungan: ' . $e->getMessage());
        return false;
    }
}
