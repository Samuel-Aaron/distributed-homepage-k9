<?php
/**
 * app/partials/node_nav.php — Switcher navigasi antar-anggota untuk simulasi failover.
 */

$membersList = [
    'm0' => 'm0 · Samuel Aaron (Ketua)',
    'm1' => 'm1 · Anggota 1',
    'm2' => 'm2 · Anggota 2',
    'm3' => 'm3 · Anggota 3',
];

$activeMember = $activeMember ?? (defined('MEMBER_ROUTE') ? MEMBER_ROUTE : 'm0');
?>
<div class="node-switcher-bar">
    <div class="node-switcher-inner">
        <span class="switcher-label">🌐 Switcher Node Halaman:</span>
        <div class="switcher-links">
            <?php foreach ($membersList as $route => $label): ?>
                <a href="?member=<?= htmlspecialchars($route) ?>" 
                   class="switcher-link <?= ($activeMember === $route) ? 'is-active' : '' ?>">
                   <?= htmlspecialchars($label) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.node-switcher-bar {
    background: #0f172a;
    color: #e2e8f0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 0.8rem;
    padding: 0.4rem 1rem;
}
.node-switcher-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.switcher-label {
    font-weight: 600;
    color: #94a3b8;
}
.switcher-links {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.switcher-link {
    color: #cbd5e1;
    text-decoration: none;
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #334155;
    background: #1e293b;
    transition: all 0.2s;
}
.switcher-link:hover {
    color: #ffffff;
    border-color: #64748b;
    background: #334155;
}
.switcher-link.is-active {
    background: #2563eb;
    color: #ffffff;
    border-color: #3b82f6;
    font-weight: 600;
}
</style>
