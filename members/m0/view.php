<?php
/**
 * ============================================================================
 * members/m0/view.php — Personal Page Samuel Aaron (Ketua / Route m0)
 * ============================================================================
 * 
 * 💡 PRINSIP ARSITEKTUR BERSIH (Clean Code):
 * 1. Separation of Concerns: Bagian atas menampung Model Data (Array PHP).
 *    Jika Anda ingin menambah portofolio baru, cukup tambah item di array $portofolio.
 * 2. Semantic HTML5: Menggunakan tag bermakna (<header>, <nav>, <main>, <section>, <footer>)
 *    bukan sekadar <div> bertumpuk.
 * 3. Security by Default: Seluruh data dicetak melewati fungsi sanitasi htmlspecialchars().
 * 4. Fault Tolerant: Siap melayani request langsung maupun failover dengan banner status.
 */

// ----------------------------------------------------------------------------
// 1. DATA MODEL (Data statis terstruktur)
// ----------------------------------------------------------------------------
$profil = [
    'nama'     => 'Samuel Aaron',
    'nim'      => '103012580024',
    'peran'    => 'Ketua Kelompok (Route m0)',
    'headline' => 'Associate Backend Engineer & Mahasiswa S1 Informatika Ekstensi Telkom University',
    'bio'      => 'Fokus pada pengembangan API tangguh, perancangan basis data relasional, dan arsitektur sistem komputasi awan terdistribusi dengan prinsip toleransi kesalahan (fault tolerance).',
    'avatar'   => 'members/m0/assets/profile.jpg',
];

$kontak = [
    'email'    => 'marbun.samuel.a@gmail.com',
    'github'   => 'https://github.com/Samuel-Aaron',
    'linkedin' => 'https://www.linkedin.com/in/samuel-aaron-marbun',
];

$portofolio = [
    [
        'id'       => 1,
        'judul'    => 'TJAHAYABIZ',
        'kategori' => 'backend',
        'periode'  => 'Sep 2024 – Mei 2025',
        'gambar'   => 'members/m0/assets/tjahayabiz.svg',
        'ringkas'  => 'Sistem web B2B untuk CV Bukit Tjahaya: penjualan bahan bangunan, pemesanan, dan inventaris.',
        'detail'   => 'Merancang arsitektur RESTful API, skema basis data relasional, dan dashboard admin terpadu.',
        'tags'     => ['PHP', 'REST API', 'MySQL', 'B2B System'],
    ],
    [
        'id'       => 2,
        'judul'    => 'Bilingual AI Evaluation & Constraint Auditing',
        'kategori' => 'ai',
        'periode'  => 'Sep 2026 – Okt 2026',
        'gambar'   => 'members/m0/assets/ai-eval.svg',
        'ringkas'  => 'Audit respon Large Language Model (LLM) berbahasa Indonesia dan Inggris.',
        'detail'   => 'Quality assurance respon model AI dalam aspek keamanan, akurasi penalaran, dan kepatuhan instruksi berlapis.',
        'tags'     => ['AI Safety', 'Quality Assurance', 'LLM Evaluation'],
    ],
    [
        'id'       => 3,
        'judul'    => 'Visual Web Development — PADMI Telkom University',
        'kategori' => 'web',
        'periode'  => 'Sep 2024 – Feb 2025',
        'gambar'   => 'members/m0/assets/padmi.svg',
        'ringkas'  => 'Pengembangan portal informasi admisi kampus Direktorat Pemasaran dan Admisi.',
        'detail'   => 'Mendukung pembaruan tampilan visual dan interaktivitas frontend portal informasi admisi on-site di Bandung.',
        'tags'     => ['Visual Web', 'Frontend', 'Telkom University'],
    ],
    [
        'id'       => 4,
        'judul'    => 'Penghitung Kunjungan Terdistribusi',
        'kategori' => 'cloud',
        'periode'  => '2026',
        'gambar'   => 'members/m0/assets/counter.svg',
        'ringkas'  => 'Aplikasi bersama kelompok: mencatat kunjungan per server untuk membuktikan pembagian beban dan failover.',
        'detail'   => 'Dibangun dengan PHP 8, MySQL/MariaDB, dan PDO prepared statements dengan pencatatan route_id.',
        'tags'     => ['PHP 8', 'Cloud Computing', 'Fault Tolerance', 'Failover'],
    ],
];
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($profil['nama']) ?> · Personal Page (m0)</title>
    <!-- File CSS Khusus Anggota m0 -->
    <link rel="stylesheet" href="members/m0/style.css">
    <link rel="icon" href="members/m0/assets/favicon.svg" type="image/svg+xml">
</head>
<body>

    <!-- 
      Komponen Bersama:
      1. node_nav.php: Navigasi switcher untuk demonstrasi failover antar-server
      2. served_by.php: Banner wajib yang mendeteksi apakah request dilayani langsung atau failover
    -->
    <?php include dirname(__DIR__, 2) . '/app/partials/node_nav.php'; ?>
    <?php include dirname(__DIR__, 2) . '/app/partials/served_by.php'; ?>

    <div class="m0-container">

        <!-- ================================================================== -->
        <!-- HEADER & NAVIGASI SEMANTIK                                         -->
        <!-- ================================================================== -->
        <header class="m0-header">
            <div class="m0-brand">
                <span class="m0-route-badge">m0</span>
                <span class="m0-brand-name"><?= htmlspecialchars($profil['nama']) ?></span>
            </div>
            <nav class="m0-nav" aria-label="Navigasi Utama">
                <a href="#profil">Profil</a>
                <a href="#pendidikan">Pendidikan</a>
                <a href="#keahlian">Keahlian</a>
                <a href="#portofolio">Portofolio</a>
                <a href="#kontak">Kontak</a>
                <a href="app/" class="m0-nav-btn">📊 App Kunjungan</a>
                <!-- Interaksi JS 1: Tombol Dark Mode -->
                <button id="themeToggle" class="m0-theme-toggle" aria-label="Ganti Tema">🌓</button>
            </nav>
        </header>

        <!-- ================================================================== -->
        <!-- MAIN CONTENT (KONTEN UTAMA)                                        -->
        <!-- ================================================================== -->
        <main>
            <!-- 1. Hero / Profil Section -->
            <section id="profil" class="m0-hero" aria-label="Profil Utama">
                <div class="m0-hero-text">
                    <span class="m0-badge"><?= htmlspecialchars($profil['peran']) ?></span>
                    <h1 class="m0-title"><?= htmlspecialchars($profil['nama']) ?></h1>
                    <p class="m0-nim">NIM: <strong><?= htmlspecialchars($profil['nim']) ?></strong></p>
                    <p class="m0-headline"><?= htmlspecialchars($profil['headline']) ?></p>
                    <p class="m0-bio"><?= htmlspecialchars($profil['bio']) ?></p>
                    <div class="m0-hero-cta">
                        <a href="#portofolio" class="m0-btn-primary">Lihat Karya</a>
                        <a href="#kontak" class="m0-btn-secondary">Hubungi Saya</a>
                    </div>
                </div>
                <div class="m0-hero-avatar">
                    <img src="<?= htmlspecialchars($profil['avatar']) ?>" 
                         alt="Foto Profil <?= htmlspecialchars($profil['nama']) ?>"
                         onerror="this.src='members/m0/assets/counter.svg'">
                </div>
            </section>

            <!-- 2. Pendidikan & Pengalaman -->
            <section id="pendidikan" class="m0-section" aria-label="Pendidikan dan Pengalaman">
                <h2 class="m0-section-title">🎓 Pendidikan & Pengalaman</h2>
                <div class="m0-grid-2">
                    <article class="m0-card">
                        <h3>Riwayat Pendidikan</h3>
                        <ul class="m0-list">
                            <li>
                                <strong>S1 Informatika (Ekstensi)</strong>
                                <span class="m0-text-muted">Telkom University · 2025 – Sekarang</span>
                            </li>
                            <li>
                                <strong>D3 Teknik Informatika</strong>
                                <span class="m0-text-muted">Lulus</span>
                            </li>
                        </ul>
                    </article>

                    <article class="m0-card">
                        <h3>Pengalaman Kerja & Proyek</h3>
                        <ul class="m0-list">
                            <li>
                                <strong>Visual Web Developer Intern</strong>
                                <span class="m0-text-muted">Direktorat Pemasaran & Admisi (PADMI) · 2024–2025</span>
                            </li>
                            <li>
                                <strong>AI Evaluator & Quality Assurance</strong>
                                <span class="m0-text-muted">Bilingual Model Evaluation · 2026</span>
                            </li>
                        </ul>
                    </article>
                </div>
            </section>

            <!-- 3. Keahlian Teknis & Non-Teknis -->
            <section id="keahlian" class="m0-section" aria-label="Keahlian">
                <h2 class="m0-section-title">🛠️ Keahlian</h2>
                <div class="m0-tags-group">
                    <span class="m0-tag">PHP 8 & PDO</span>
                    <span class="m0-tag">MySQL / MariaDB</span>
                    <span class="m0-tag">RESTful Architecture</span>
                    <span class="m0-tag">Git & Team Collaboration</span>
                    <span class="m0-tag">Cloud Infrastructure (GCP)</span>
                    <span class="m0-tag">High Availability & Failover Design</span>
                </div>
            </section>

            <!-- 4. Portofolio (Dengan Interaksi JS 2: Filter Interaktif) -->
            <section id="portofolio" class="m0-section" aria-label="Portofolio">
                <div class="m0-section-header-flex">
                    <h2 class="m0-section-title" style="margin-bottom:0;">💼 Portofolio Terpilih</h2>
                    <!-- Tombol Filter Interaktif Kategori -->
                    <div class="m0-filter-bar" id="portfolioFilter">
                        <button class="m0-filter-btn active" data-filter="all">Semua</button>
                        <button class="m0-filter-btn" data-filter="backend">Backend</button>
                        <button class="m0-filter-btn" data-filter="ai">AI</button>
                        <button class="m0-filter-btn" data-filter="web">Web</button>
                        <button class="m0-filter-btn" data-filter="cloud">Cloud</button>
                    </div>
                </div>

                <!-- Grid Render Portofolio dari Array PHP -->
                <div class="m0-portfolio-grid" id="portfolioGrid">
                    <?php foreach ($portofolio as $item): ?>
                        <article class="m0-portfolio-card" data-category="<?= htmlspecialchars($item['kategori']) ?>">
                            <div class="m0-portfolio-thumb">
                                <img src="<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['judul']) ?>">
                            </div>
                            <div class="m0-portfolio-body">
                                <span class="m0-portfolio-period"><?= htmlspecialchars($item['periode']) ?></span>
                                <h3><?= htmlspecialchars($item['judul']) ?></h3>
                                <p><?= htmlspecialchars($item['ringkas']) ?></p>
                                <div class="m0-portfolio-tags">
                                    <?php foreach ($item['tags'] as $tag): ?>
                                        <span><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 5. Kontak Publik -->
            <section id="kontak" class="m0-section" aria-label="Kontak">
                <h2 class="m0-section-title">📬 Kontak</h2>
                <p class="m0-text-muted">Hubungi saya untuk koordinasi tugas kuliah, proyek teknologi, atau diskusi rekayasa perangkat lunak:</p>
                <div class="m0-contact-grid">
                    <a href="mailto:<?= htmlspecialchars($kontak['email']) ?>" class="m0-contact-card">
                        📧 <span><?= htmlspecialchars($kontak['email']) ?></span>
                    </a>
                    <a href="<?= htmlspecialchars($kontak['linkedin']) ?>" target="_blank" rel="noopener" class="m0-contact-card">
                        💼 <span>LinkedIn Profil</span>
                    </a>
                    <a href="<?= htmlspecialchars($kontak['github']) ?>" target="_blank" rel="noopener" class="m0-contact-card">
                        🐙 <span>GitHub Profil</span>
                    </a>
                </div>
            </section>
        </main>

        <!-- ================================================================== -->
        <!-- FOOTER SEMANTIK                                                    -->
        <!-- ================================================================== -->
        <footer class="m0-footer">
            <p>© 2026 <?= htmlspecialchars($profil['nama']) ?> · NIM <?= htmlspecialchars($profil['nim']) ?></p>
            <p class="m0-text-muted" style="font-size:0.8rem; margin-top:0.25rem;">Tugas 1 Komputasi Awan (Pra-UTS) · Kelompok 9</p>
        </footer>
    </div>

    <!-- File JavaScript Bersih & Terisolasi -->
    <script src="members/m0/main.js"></script>
</body>
</html>
