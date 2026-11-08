<?php
/**
 * app/lib/kunjungan.php — Pencatatan kunjungan dengan buffer offline (T-02/T-08).
 *
 * Setiap kali halaman dibuka, satu baris disimpan ke tabel `kunjungan`:
 *   - route_id       : server fisik yang melayani request (MEMBER_ROUTE dari .env)
 *   - target_member  : profil/halaman yang dilihat (m0..m3, atau 'app' untuk dashboard)
 *   - is_failover    : 1 jika server ini melayani profil milik anggota lain
 *   - ip_hash        : SHA-256 dari IP (IP asli tidak pernah disimpan)
 *
 * Kunjungan ditulis ke antrean lokal dahulu, lalu disinkronkan jika DB tersedia.
 * Kegagalan DB tidak menghapus antrean dan tidak menghentikan halaman.
 */

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/offline_queue.php';

/**
 * Catat satu kunjungan otomatis (jenis 'auto').
 *
 * @param PDO|null $pdo           Koneksi dari db(); null = database mati.
 * @param string   $targetMember  Halaman yang dilihat: m0..m3 atau 'app'.
 * @return bool                   true jika tersimpan di antrean/DB, false jika dilewati/gagal.
 */
function catat_kunjungan(?PDO $pdo, string $targetMember): bool
{
    // Hanya catat pemuatan halaman biasa, bukan HEAD/POST/dll.
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
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
        $queue = new OfflineVisitQueue(dirname(__DIR__) . '/storage/pending_sync.json');
        $accepted = $queue->record($pdo, [
            'event_id'      => bin2hex(random_bytes(16)),
            'route_id'      => $serverRoute,
            'target_member' => $targetMember,
            'is_failover'   => $isFailover ? 1 : 0,
            'jenis'         => 'auto',
            'ip_hash'       => get_ip_hash(),
            'user_agent'    => $userAgent,
            'occurred_at'   => time(),
        ]);
        $sudahDicatat = $accepted;
        return $accepted;
    } catch (Throwable $e) {
        error_log('[Kunjungan] Gagal mencatat kunjungan: ' . $e->getMessage());
        return false;
    }
}
