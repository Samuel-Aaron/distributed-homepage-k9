<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/profile.php';

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$m2_nama   = 'Akbar Saputra Hardian';
$m2_nim    = '103012400193';
$m2_route  = 'm2';

$photoFilename = 'members/m2/assets/images/foto-profil.jpg';
$hasRealPhoto  = is_file(dirname(__DIR__, 2) . '/' . $photoFilename);
$profilePhoto  = $hasRealPhoto ? $photoFilename : 'members/m2/assets/images/avatar.svg';
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark light">
    <meta name="description" content="Personal page dan portofolio <?= e($m2_nama) ?>, mahasiswa informatika dengan minat cybersecurity dan pengembangan web.">
    <title><?= e($m2_nama) ?> | Personal Page (m2)</title>
    <link rel="icon" type="image/svg+xml" href="members/m2/assets/images/favicon.svg">
    <link rel="stylesheet" href="members/m2/assets/css/style.css">
    <script src="members/m2/assets/js/app.js" defer></script>
</head>
<body>
    <div class="scroll-progress" id="scroll-progress" aria-hidden="true"><span></span></div>
    <a class="skip-link" href="#konten">Lewati ke konten</a>
    <div class="page-grain" aria-hidden="true"></div>

    <!-- 1. Node Switcher & Server Banner -->
    <?php include dirname(__DIR__, 2) . '/app/partials/node_nav.php'; ?>
    <?php include dirname(__DIR__, 2) . '/app/partials/served_by.php'; ?>

    <header class="header" id="beranda">
        <div class="container header-row">
            <a class="brand" href="#beranda" aria-label="Beranda Akbar Saputra Hardian"><span class="brand-mark">A<span>.</span></span><span class="brand-caption">AKBAR<span class="brand-muted"> / PORTFOLIO</span></span></a>
            <nav class="primary-nav" id="primary-nav" aria-label="Navigasi utama">
                <a href="#tentang">Tentang</a>
                <a href="#keahlian">Keahlian</a>
                <a href="#portofolio">Portofolio</a>
                <a href="#perjalanan">Pendidikan</a>
                <a href="#aplikasi-bersama">Aplikasi Bersama</a>
                <a href="#kontak">Kontak</a>
            </nav>
            <div class="header-actions">
                <button class="theme-toggle" id="theme-toggle" type="button" aria-label="Ganti tampilan terang atau gelap" title="Mode terang/gelap"><span id="theme-icon" aria-hidden="true">☼</span></button>
                <button class="menu-toggle" id="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Buka navigasi"><span></span><span></span><span></span></button>
            </div>
        </div>
    </header>

    <main id="konten">
        <section class="hero container" aria-labelledby="hero-heading">
            <div class="hero-copy">
                <p class="eyebrow"><span class="status-dot" aria-hidden="true"></span> PERSONAL PAGE <span class="eyebrow-slash">/</span> 2026</p>
                <p class="hello">HELLO, I’M</p>
                <h1 id="hero-heading">AKBAR <br><span>SAPUTRA</span><br>HARDIAN<span class="accent">.</span></h1>
                <div class="role"><span class="role-caret">&gt;_</span> <?= e($profile['tagline']) ?></div>
                <p class="hero-description"><?= e($profile['intro']) ?></p>
                <div class="hero-buttons">
                    <a class="btn btn-primary" href="#portofolio">Lihat portofolio <span aria-hidden="true">↗</span></a>
                    <a class="btn btn-border" href="#tentang">Tentang saya <span aria-hidden="true">↓</span></a>
                </div>
                <div class="hero-socials">
                    <a href="<?= e($profile['linkedin']) ?>" target="_blank" rel="noopener noreferrer">LINKEDIN ↗</a>
                    <span aria-hidden="true">/</span>
                    <a href="<?= e($profile['merdekasiber']) ?>" target="_blank" rel="noopener noreferrer">MERDEKASIBER ↗</a>
                </div>
            </div>
            <div class="hero-card" data-reveal data-reveal-delay="1">
                <div class="card-topline"><span><i class="live-dot"></i> PROFILE CARD</span><span>ID / 001</span></div>
                <div class="portrait-wrap">
                    <div class="portrait-ring portrait-ring-one"></div>
                    <div class="portrait-ring portrait-ring-two"></div>
                    <img class="portrait" src="<?= e($profilePhoto) ?>" alt="<?= $hasRealPhoto ? 'Foto profil ' . e($m2_nama) : 'Avatar inisial untuk ' . e($m2_nama) ?>" width="300" height="300">
                    <span class="floating-label floating-left">CYBERSECURITY</span>
                    <span class="floating-label floating-right">STUDENT.DEV</span>
                </div>
                <div class="card-identity"><div><span class="card-kicker">STUDENT PROFILE</span><strong><?= e($m2_nama) ?></strong><small><?= e($profile['campus']) ?> · <?= e($profile['location']) ?></small></div><div class="card-symbol" aria-hidden="true">⌘</div></div>
                <?php if (!$hasRealPhoto): ?><p class="photo-note">Foto asli belum tersedia — saat ini menggunakan avatar.</p><?php endif; ?>
                <div class="card-bottom"><span>WEB · SECURITY · DATA</span><span>2026 ©</span></div>
            </div>
            <div class="hero-bottom"><span><i></i> SCROLL TO EXPLORE</span><span>RESEARCH • BUILD • SECURE</span></div>
        </section>

        <div class="stats-band"><div class="container stats-grid">
            <div><strong><span class="stat-number" data-count data-count-to="1" data-count-pad="2">01</span><span class="stat-suffix">.</span></strong><small>PERSONAL PAGE<br>INDIVIDU</small></div>
            <div><strong><span class="stat-number" data-count data-count-to="3" data-count-pad="2">03</span><span class="stat-suffix">+</span></strong><small>PROYEK DAN<br>PORTOFOLIO</small></div>
            <div><strong><span class="stat-prefix">PHP</span><span class="stat-number" data-count data-count-to="8">8</span></strong><small>BACKEND LOKAL<br>APACHE / XAMPP</small></div>
            <div><strong><span class="stat-number" data-count data-count-to="2">2</span><span class="stat-suffix">+</span></strong><small>INTERAKSI<br>JAVASCRIPT</small></div>
        </div></div>

        <section class="section about-section container" id="tentang" aria-labelledby="about-heading">
            <div class="section-heading" data-reveal><span class="section-number">01 / ABOUT ME</span><h2 id="about-heading">Penasaran, belajar, <br><span>dan terus membangun.</span></h2></div>
            <div class="about-grid"><div class="about-text" data-reveal><p class="lead">Saya <strong><?= e($m2_nama) ?></strong>, mahasiswa Informatika yang tertarik pada keamanan sistem dan penerapan teknologi yang bertanggung jawab.</p><p><?= e($profile['description']) ?></p><p>Selain kemampuan teknis, saya terus berlatih menyampaikan temuan dengan jelas, bekerja sama dalam tim, dan menganalisis masalah secara terstruktur.</p><div class="about-chips"><span>#CyberSecurity</span><span>#PHPDevelopment</span><span>#LearningByDoing</span></div></div><aside class="bio-panel" data-reveal data-reveal-delay="1"><div class="panel-caption">// BIODATA MAHASISWA</div><dl><div><dt>NAMA</dt><dd><?= e($m2_nama) ?></dd></div><div><dt>NIM</dt><dd><?= e($m2_nim) ?></dd></div><div><dt>PROGRAM STUDI</dt><dd>S1 Informatika</dd></div><div><dt>PERGURUAN TINGGI</dt><dd><?= e($profile['campus']) ?></dd></div><div><dt>STATUS</dt><dd>Mahasiswa Aktif</dd></div><div><dt>ROUTE SERVER</dt><dd><?= e($m2_route) ?></dd></div></dl></aside></div>
        </section>

        <section class="section section-tinted" id="keahlian" aria-labelledby="skills-heading"><div class="container"><div class="section-heading section-head-row" data-reveal><div><span class="section-number">02 / MY SKILLS</span><h2 id="skills-heading">Keterampilan yang <span>terus diasah.</span></h2></div><p>Gabungan keterampilan teknis dan interpersonal untuk menyelesaikan masalah secara sistematis.</p></div><div class="skills-grid"><?php foreach ($skillGroups as $skillIndex => $group): ?><article class="skill-card" data-reveal<?= $skillIndex > 0 ? ' data-reveal-delay="' . min($skillIndex, 3) . '"' : '' ?>><div class="skill-card-top"><span class="skill-numeral"><?= e($group['icon']) ?></span><span class="skill-arrow">↗</span></div><h3><?= e($group['title']) ?></h3><p><?= e($group['description']) ?></p><div class="skill-tags"><?php foreach ($group['skills'] as $skill): ?><span><?= e($skill) ?></span><?php endforeach; ?></div></article><?php endforeach; ?></div></div></section>

        <section class="section container" id="portofolio" aria-labelledby="portfolio-heading"><div class="section-heading section-head-row" data-reveal><div><span class="section-number">03 / SELECTED WORKS</span><h2 id="portfolio-heading">Portofolio <span>&amp; proyek.</span></h2></div><p>Tiga aktivitas pilihan yang merepresentasikan proses belajar, pengujian, dan pengembangan.</p></div><div class="filters" role="group" aria-label="Filter kategori portofolio"><button class="filter-btn is-active" type="button" data-filter="all" aria-pressed="true">Semua <span>03</span></button><button class="filter-btn" type="button" data-filter="engineering" aria-pressed="false">Engineering</button><button class="filter-btn" type="button" data-filter="research" aria-pressed="false">Research</button><button class="filter-btn" type="button" data-filter="labs" aria-pressed="false">Labs</button></div><div class="projects-grid" id="project-list"><?php foreach ($projects as $projectIndex => $project): ?><article class="project-card" data-category="<?= e($project['category']) ?>" data-reveal<?= $projectIndex > 0 ? ' data-reveal-delay="' . min($projectIndex, 3) . '"' : '' ?>><div class="project-image"><img src="<?= e($project['image']) ?>" width="800" height="480" loading="lazy" alt="<?= e($project['image_alt']) ?>"><span class="project-image-number"><?= e($project['number']) ?> / 03</span></div><div class="project-content"><div class="project-type"><?= e($project['type']) ?></div><h3><?= e($project['title']) ?></h3><p><?= e($project['description']) ?></p><div class="project-tags"><?php foreach ($project['tags'] as $tag): ?><span><?= e($tag) ?></span><?php endforeach; ?></div></div></article><?php endforeach; ?></div><p id="filter-announcement" class="sr-only" role="status" aria-live="polite"></p></section>

        <section class="section section-tinted" id="perjalanan" aria-labelledby="journey-heading"><div class="container"><div class="section-heading" data-reveal><span class="section-number">04 / MY JOURNEY</span><h2 id="journey-heading">Pendidikan <span>& pengalaman.</span></h2></div><div class="journey-grid"><div class="journey-col"><h3><span aria-hidden="true">↗</span> Pendidikan</h3><div class="timeline" data-timeline><?php foreach ($educations as $item): ?><article class="timeline-item" data-reveal><span><?= e($item['period']) ?></span><h4><?= e($item['title']) ?></h4><strong><?= e($item['organization']) ?></strong><p><?= e($item['detail']) ?></p></article><?php endforeach; ?></div></div><div class="journey-col"><h3><span aria-hidden="true">↗</span> Pengalaman & Aktivitas</h3><div class="timeline" data-timeline><?php foreach ($experiences as $item): ?><article class="timeline-item" data-reveal><span><?= e($item['period']) ?></span><h4><?= e($item['title']) ?></h4><strong><?= e($item['organization']) ?></strong><p><?= e($item['detail']) ?></p></article><?php endforeach; ?></div></div></div></div></section>

        <section class="section container" id="aplikasi-bersama" aria-labelledby="group-heading"><div class="section-heading section-head-row" data-reveal><div><span class="section-number">05 / GROUP PROJECT</span><h2 id="group-heading">Aplikasi <span>bersama.</span></h2></div><p>Bagian integrasi untuk satu aplikasi yang dikembangkan oleh kelompok bersama.</p></div><div class="group-panel" data-reveal><div class="group-info"><span class="group-label"><span class="status-dot"></span> APLIKASI KELOMPOK TERHUBUNG</span><h3>Aplikasi kelompok sudah tersedia.</h3><p>Aplikasi Penghitung Kunjungan Terdistribusi dapat diakses melalui tombol berikut.</p><a class="btn btn-primary" href="app/">Buka dashboard kelompok ↗</a></div><div class="group-preview"><iframe title="Pratinjau aplikasi bersama" src="app/" loading="lazy"></iframe></div></div></section>

        <section class="section contact-section" id="kontak" aria-labelledby="contact-heading"><div class="container contact-shell" data-reveal><span class="section-number section-number-light">06 / GET IN TOUCH</span><h2 id="contact-heading">Mari berdiskusi <br><span>dan berkolaborasi.</span></h2><p>Terbuka untuk diskusi seputar keamanan siber, pengembangan web, kolaborasi riset, dan pembelajaran teknologi.</p><div class="contact-buttons"><a class="btn btn-light" href="mailto:<?= e($profile['email']) ?>">Kirim email <span aria-hidden="true">↗</span></a><a class="btn btn-contact-outline" href="<?= e($profile['linkedin']) ?>" target="_blank" rel="noopener noreferrer">Lihat LinkedIn <span aria-hidden="true">↗</span></a></div><div class="contact-email"><span>EMAIL</span><strong><?= e($profile['email']) ?></strong><button class="copy-email" id="copy-email" type="button" data-email="<?= e($profile['email']) ?>">Salin alamat ↗</button></div><p id="copy-feedback" role="status" aria-live="polite" class="copy-feedback"></p></div></section>
    </main>
    <footer class="footer"><div class="container footer-row"><div><span class="footer-brand">A<span>.</span></span><p><?= e($m2_nama) ?><br><small>Personal Page · Tugas 1 Komputasi Awan</small></p></div><div class="footer-links"><a href="#beranda">Kembali ke atas ↑</a><span>© <?= date('Y') ?> · Dibangun dengan PHP, CSS & JavaScript.</span></div></div>    </footer>
    <button class="to-top" id="to-top" type="button" aria-label="Kembali ke atas halaman">
        <svg class="to-top-ring" viewBox="0 0 40 40" aria-hidden="true" focusable="false">
            <circle class="to-top-track" cx="20" cy="20" r="18"></circle>
            <circle class="to-top-fill" id="to-top-fill" cx="20" cy="20" r="18"></circle>
        </svg>
        <span aria-hidden="true">↑</span>
    </button>
</body>
</html>
