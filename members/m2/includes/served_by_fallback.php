<?php
// Banner sementara untuk tahap personal page.
// Setelah repo aplikasi kelompok tersedia, index.php memakai app/partials/served_by.php.
?>
<div class="served-banner" role="status">
    <div class="container served-banner-inner">
        <span class="served-status"><span class="served-led"></span> LOCAL SERVER / PHP</span>
        <span>Anda dilayani oleh laman <strong><?= e(MEMBER_NAMA) ?></strong> (<strong><?= e(MEMBER_NIM) ?></strong>)</span>
        <span class="served-route">ROUTE <?= e(MEMBER_ROUTE) ?></span>
    </div>
</div>
