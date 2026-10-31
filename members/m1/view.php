<?php
/**
 * members/m1/view.php — Personal Page Anggota 1 (Placeholder Skeleton)
 * Anggota 1 dapat mengganti file ini dengan desain dan konten pribadinya.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anggota 1 · Personal Page (m1)</title>
    <style>
        body { font-family: -apple-system, sans-serif; background: #f8fafc; color: #0f172a; margin: 0; padding: 0; }
        .wrapper { max-width: 900px; margin: 2rem auto; padding: 1.5rem; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; }
        .badge { background: #e0e7ff; color: #3730a3; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem; }
    </style>
</head>
<body>
    <?php include dirname(__DIR__, 2) . '/app/partials/node_nav.php'; ?>
    <?php include dirname(__DIR__, 2) . '/app/partials/served_by.php'; ?>

    <div class="wrapper">
        <span class="badge">Route m1</span>
        <h1>Personal Page: Anggota 1</h1>
        <p>Halaman ini disiapkan untuk diisi oleh Anggota 1 (Profil, Pengalaman, Portofolio, dan 2 Interaksi JS).</p>
    </div>
</body>
</html>
