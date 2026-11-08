<?php
/**
 * app/partials/served_by.php — Banner identitas server & status failover.
 *
 * Menampilkan siapa pemilik konten halaman dan server fisik mana yang melayani request.
 */

$currentServer = defined('MEMBER_ROUTE') ? MEMBER_ROUTE : 'm?';
$serverName    = defined('MEMBER_NAMA')  ? MEMBER_NAMA  : 'Server Lokal';
$serverNim     = defined('MEMBER_NIM')   ? MEMBER_NIM   : '-';

$activeMember  = $activeMember ?? $currentServer;
$isFailover    = ($activeMember !== $currentServer);

// Path dasar aplikasi (mis. "/personal"), dihitung dari skrip yang sedang berjalan,
// agar tautan dashboard tidak berlipat menjadi app/app saat sudah berada di /app/.
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath   = preg_match('#^(.*?)/app(?:/|$)#', $scriptName, $m) ? $m[1] : rtrim(dirname($scriptName), '/');
$dashboardUrl = $basePath . '/app/';
?>
<div class="served-by-banner <?= $isFailover ? 'banner-failover' : 'banner-normal' ?>">
    <div class="banner-container">
        <span class="banner-badge"><?= $isFailover ? '⚠️ MODE FAILOVER' : '⚡ SERVER AKTIF' ?></span>
        <div class="banner-text">
            <?php if ($isFailover): ?>
                <span>Menampilkan profil <strong><?= htmlspecialchars(strtoupper($activeMember)) ?></strong></span>
                <span class="banner-sub">Dilayani oleh Server Cadangan: <strong><?= htmlspecialchars($serverName) ?> (<?= htmlspecialchars($serverNim) ?>) [<?= htmlspecialchars($currentServer) ?>]</strong></span>
            <?php else: ?>
                <span>Anda dilayani oleh laman <strong><?= htmlspecialchars($serverName) ?></strong> (<?= htmlspecialchars($serverNim) ?>) [<?= htmlspecialchars($currentServer) ?>]</span>
            <?php endif; ?>
        </div>
        <div class="banner-nav">
            <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="banner-btn">📊 Dashboard Kunjungan</a>
        </div>
    </div>
</div>

<style>
.served-by-banner {
    width: 100%;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    box-sizing: border-box;
    z-index: 9999;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}
.banner-normal {
    background: #f0fdf4;
    color: #166534;
    border-color: #bbf7d0;
}
.banner-failover {
    background: #fffbeb;
    color: #92400e;
    border-color: #fde68a;
}
.banner-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.banner-badge {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    letter-spacing: 0.5px;
}
.banner-normal .banner-badge {
    background: #dcfce7;
    color: #15803d;
}
.banner-failover .banner-badge {
    background: #fef3c7;
    color: #b45309;
}
.banner-text {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
    flex: 1;
}
.banner-sub {
    font-size: 0.8rem;
    opacity: 0.9;
}
.banner-btn {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    color: #1e293b;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    transition: all 0.2s;
}
.banner-btn:hover {
    background: #f1f5f9;
}
</style>
