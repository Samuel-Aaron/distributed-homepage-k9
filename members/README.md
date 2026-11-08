# Panduan Direktori Personal Page Anggota (`members/`)

Direktori ini menampung kode personal page untuk masing-masing dari 4 anggota kelompok.

## Struktur Folder Anggota:

```text
members/
├── m0/   ← Ketua: Samuel Aaron
│   ├── view.php
│   ├── style.css
│   └── main.js
├── m1/   ← Anggota 1
│   ├── view.php
│   ├── style.css
│   └── main.js
├── m2/   ← Anggota 2
│   ├── view.php
│   ├── style.css
│   └── main.js
└── m3/   ← Anggota 3
    ├── view.php
    ├── style.css
    └── main.js
```

## Ketentuan Setiap Anggota:
1. **File `view.php`:**
   - Memuat profil lengkap: Nama, NIM, foto avatar, deskripsi.
   - Riwayat pendidikan & pengalaman.
   - Minimal 3 portofolio item (judul, gambar, deskripsi).
   - Kontak publik (email / LinkedIn / GitHub).
   - Memanggil banner server di bagian paling atas:
     ```php
     <?php include dirname(__DIR__, 2) . '/app/partials/served_by.php'; ?>
     ```
   - Memanggil navigasi switcher node:
     ```php
     <?php include dirname(__DIR__, 2) . '/app/partials/node_nav.php'; ?>
     ```
2. **File `style.css`:**
   - Styling CSS kustom dan responsif untuk mobile dan laptop.
3. **File `main.js`:**
   - Minimal memuat 2 interaksi JavaScript (misalnya: Dark/Light Mode, Filter Portofolio, Modal Dialog, atau Animasi).
    
