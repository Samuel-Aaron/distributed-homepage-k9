<?php
declare(strict_types=1);

$profile = [
    'tagline' => 'Informatics Student • Independent Security Researcher',
    'headline' => 'Membangun teknologi dengan perspektif keamanan.',
    'intro' => 'Mahasiswa S1 Informatika di Telkom University dengan minat pada keamanan aplikasi web, responsible disclosure, data analytics, dan infrastruktur yang lebih aman.',
    'description' => 'Saya belajar menghubungkan pengetahuan pengembangan perangkat lunak dan keamanan siber melalui praktik laboratorium, kajian aplikasi web, dokumentasi hasil pengujian, serta eksperimen isolasi sistem dalam proyek akademik.',
    'location' => 'Indonesia',
    'campus' => 'Telkom University',
    'email' => 'saputrahardianakbar@gmail.com',
    'linkedin' => 'https://www.linkedin.com/in/akbar-saputra-hardian-b4419232b/',
    'merdekasiber' => 'https://portal.merdekasiber.com/p/akbar-saputra-hardian',
];

$skillGroups = [
    [
        'title' => 'Keahlian Teknis',
        'icon' => '01',
        'description' => 'Keamanan aplikasi dan rekayasa sistem.',
        'skills' => ['Web Application Security', 'OWASP Top 10', 'Burp Suite', 'Kali Linux', 'Docker & Sandbox', 'PHP & MySQL', 'JavaScript', 'Git'],
    ],
    [
        'title' => 'Keahlian Non-Teknis',
        'icon' => '02',
        'description' => 'Keterampilan yang mendukung kolaborasi.',
        'skills' => ['Problem Solving', 'Technical Reporting', 'Analytical Thinking', 'Team Collaboration', 'Responsible Disclosure', 'Communication'],
    ],
];

$projects = [
    [
        'number' => '01',
        'title' => 'Hermes Agent Security Sandbox',
        'category' => 'engineering',
        'type' => 'Proyek Akademik • Dalam Pengembangan',
        'description' => 'Perancangan lingkungan isolasi untuk Hermes Agent menggunakan Docker, kontrol jaringan, dan penguatan keamanan container sebagai bagian eksplorasi proyek akademik.',
        'image' => 'members/m2/assets/images/project-sandbox.svg',
        'image_alt' => 'Ilustrasi arsitektur sandbox Docker dan guard untuk Hermes Agent',
        'tags' => ['Docker', 'Isolation', 'Security Guard'],
    ],
    [
        'number' => '02',
        'title' => 'Web Security Assessment',
        'category' => 'research',
        'type' => 'Riset Keamanan Web',
        'description' => 'Latihan analisis respons HTTP, konfigurasi aplikasi, permukaan API, dan penyusunan laporan teknis dengan pendekatan pengujian yang berizin.',
        'image' => 'members/m2/assets/images/project-assessment.svg',
        'image_alt' => 'Ilustrasi dashboard analisis keamanan aplikasi web dan respons HTTP',
        'tags' => ['Burp Suite', 'HTTP', 'OWASP'],
    ],
    [
        'number' => '03',
        'title' => 'CTF & Web Security Labs',
        'category' => 'labs',
        'type' => 'Latihan Praktik Keamanan',
        'description' => 'Pembelajaran terstruktur melalui laboratorium CTF dan tantangan web security untuk memperdalam pemahaman autentikasi, validasi input, dan kelas kerentanan web.',
        'image' => 'members/m2/assets/images/project-labs.svg',
        'image_alt' => 'Ilustrasi puzzle CTF dan terminal latihan keamanan siber',
        'tags' => ['CTF', 'Security Labs', 'Learning'],
    ],
];

$experiences = [
    ['period' => '2026 – Sekarang', 'title' => 'Independent Security Researcher', 'organization' => 'Pembelajaran dan riset mandiri', 'detail' => 'Analisis aplikasi web, dokumentasi teknis, dan pendekatan responsible disclosure.'],
    ['period' => '2026 – Sekarang', 'title' => 'CTF & Web Security Learning', 'organization' => 'Latihan mandiri / platform laboratorium', 'detail' => 'Praktik keamanan web di lingkungan pembelajaran yang disediakan.'],
    ['period' => '2024', 'title' => 'Logistik & Lapangan', 'organization' => 'Jabar Bergerak Zillenial', 'detail' => 'Dukungan koordinasi dan operasional kegiatan sosial.'],
];

$educations = [
    ['period' => '2024 – Sekarang', 'title' => 'S1 Informatika', 'organization' => 'Telkom University', 'detail' => 'Fokus pengembangan kompetensi pemrograman, keamanan siber, dan analisis sistem.'],
    ['period' => '2020 – 2023', 'title' => 'Ilmu Pengetahuan Alam', 'organization' => 'SMA Angkasa 1 Jakarta', 'detail' => 'Pendidikan menengah.'],
];
