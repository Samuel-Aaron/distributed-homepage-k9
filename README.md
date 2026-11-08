# Homepage Lokal & Penghitung Kunjungan Terdistribusi

Proyek ini dibangun untuk memenuhi tugas **Tugas 1 Komputasi Awan (Pra-UTS)**: Personal Page dan Aplikasi Terdistribusi dengan toleransi kesalahan (*fault tolerance*) dan replikasi multi-node. Menggunakan arsitektur konfigurasi modern **Pure .env**.

---

## 📋 Progress Tracker & Kolaborasi Tim

Untuk melihat pembagian tugas, mengklaim fitur yang ingin dikerjakan, dan memantau status anggota, silakan buka dokumen khusus:
👉 [**`PROGRESS.md` (Buka Tracker Tugas Kelompok 9)**](PROGRESS.md)

---

## 👥 Anggota Kelompok & Alokasi Route

| Route | Posisi | Nama | NIM | Folder Kerja |
|---|---|---|---|---|
| `m0` | Ketua | Samuel Aaron | `103012580024` | `members/m0/` |
| `m1` | Anggota | Marthin Tambunan | `103012580055` | `members/m1/` |
| `m2` | Anggota | Akbar Saputra Hardian | `103012400193` | `members/m2/` |
| `m3` | Anggota | *(TBD)* | `-` | `members/m3/` |

---

## 🚀 Panduan Instalasi Lokal (Untuk Seluruh Anggota)

### 1. Masuk ke Folder Web
Clone repositori ini langsung ke folder web server Anda (XAMPP: `C:\xampp\htdocs\personal`):
```bash
cd C:\xampp\htdocs
git clone <URL-REPO-GITHUB> personal
cd personal
```

### 2. Konfigurasi Lingkungan (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
copy .env.example .env
```
Buka file `.env`, lalu sesuaikan nilainya:
* `MEMBER_ROUTE`: `m1` (sesuai route anggota Anda).
* `MEMBER_NAMA`: Nama lengkap Anda.
* `MEMBER_NIM`: NIM Anda.
* `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`: Kredensial database lokal Anda.

*(Catatan: File `.env` bersifat lokal dan dilindungi oleh `.gitignore` sehingga tidak akan bocor ke GitHub).*

### 3. Setup Database MySQL di phpMyAdmin
1. Buka `http://localhost/phpmyadmin/`.
2. Klik tab **Import** → pilih file `app/sql/schema.sql` → klik **Go**.
3. Di tab **SQL**, jalankan query pembuatan user aplikasi:
   ```sql
   CREATE USER IF NOT EXISTS 'app_local'@'localhost' IDENTIFIED BY 'password_lokal';
   GRANT SELECT, INSERT, UPDATE, DELETE ON kelompok_db.* TO 'app_local'@'localhost';
   FLUSH PRIVILEGES;
   ```

### 4. Akses Melalui Browser
* Homepage Personal: `http://localhost/personal/`
* Dashboard Aplikasi Bersama: `http://localhost/personal/app/`

Halaman Marthin (m1) dapat dibuka melalui `?member=m1` pada URL utama proyek.
Kode berada di `members/m1/view.php`, `style.css`, `main.js`, dan `images/`.
Interaksi JavaScript: lightbox gambar portofolio dan penanda navigasi aktif sesuai bagian yang dipilih.
Untuk menjadikan m1 halaman default node lokal, isi `.env` dengan `MEMBER_ROUTE=m1`,
`MEMBER_NAMA="Marthin Tambunan"`, dan `MEMBER_NIM=103012580055`.

## T-08: buffer offline dan sinkronisasi

Setiap kunjungan GET ke profil atau dashboard ditulis dahulu ke
`app/storage/pending_sync.json` pada node yang melayani. Jika database tersedia,
request yang sama mengirim seluruh antrean dengan INSERT multi-baris, maksimal
100 kunjungan per statement dalam satu transaksi. Jika database gagal, profil
tetap tampil dan antrean dipertahankan.

Sinkronisasi otomatis dipicu oleh kunjungan GET berikutnya setelah database pulih,
bukan oleh worker latar belakang. Tanpa request baru, antrean tetap menunggu.
`event_id` unik mencegah duplikasi jika proses terhenti setelah COMMIT tetapi
sebelum antrean dikosongkan. Waktu kunjungan asli, server asal, profil tujuan,
status failover, dan hash IP tetap dipertahankan.

### Instalasi pada database yang sudah ada

1. Pada phpMyAdmin database pusat, jalankan **satu kali** file
   `app/sql/migrations/008_offline_sync.sql` memakai akun administrator.
   Migrasi menambah kolom nullable `event_id` dan indeks unik; data lama tidak
   dihapus. Akun aplikasi tetap memakai hak SELECT/INSERT/UPDATE/DELETE.
2. Deploy kode T-08 ke semua node. Instalasi database baru cukup menggunakan
   `app/sql/schema.sql`, yang sudah berisi kolom tersebut; jangan jalankan migrasi
   lagi pada skema baru.
3. Pastikan Apache/PHP dapat menulis ke direktori `app/storage/`, termasuk membuat
   file sementara dan mengganti file antrean. Direktori ini harus berada pada
   disk lokal masing-masing node, bukan folder jaringan bersama.
4. Pastikan `DB_HOST` di `.env` hanya IP/hostname, tanpa `http://`, path, atau `/`
   di akhir. Identitas `MEMBER_ROUTE` tetap milik node masing-masing.
5. Uji URL `/app/storage/pending_sync.json` dari browser: harus ditolak dengan
   403/404. `.htaccess` bawaan menolak seluruh akses storage pada Apache dengan
   AllowOverride aktif. Pada server lain, buat aturan penolakan setara.

Jangan hapus antrean, file `.lock`, atau mengganti direktori storage saat aplikasi
sedang berjalan. Lock terpisah menyerialkan penulisan dan sinkronisasi; JSON diganti
secara atomik. File runtime di storage diabaikan Git, sedangkan `.htaccess` tetap
diikutsertakan. Tidak ada IP mentah di antrean; user-agent tetap disimpan.

### Verifikasi dan batasan

- Putuskan koneksi DB pada node percobaan, buka beberapa halaman, lalu periksa
  antrean lokal. Pulihkan koneksi dan buka satu halaman: antrean menjadi `[]`,
  sedangkan kunjungan lama dan baru muncul pada DB pusat tepat sekali.
- Kegagalan SQL, termasuk migrasi yang belum dijalankan, mempertahankan antrean.
  Kesalahan dicatat dengan prefix `[Offline Sync]` pada error log PHP.
- Antrean rusak tidak ditimpa otomatis. Simpan salinan dan perbaiki penyebabnya;
  kunjungan baru tidak dapat diterima selama antrean tidak dapat dibaca.
- Disk penuh, izin tulis yang salah, kehilangan disk/laptop, dan kegagalan daya
  tidak dijamin oleh buffer ini. Ini bukan replikasi atau failover database.
  Antrean bertambah selama offline, sehingga ruang disk perlu dijaga.

Regresi integrasi memakai MariaDB/MySQL nyata dan database acak khusus pengujian:

```powershell
C:\xampp\php\php.exe tests/offline_sync.php
```

Default pengujian: server lokal `127.0.0.1:3306`, user `root`, password kosong.
Untuk server pengujian lain, gunakan variabel `T08_TEST_DSN` (tanpa `dbname`),
`T08_TEST_USER`, dan `T08_TEST_PASS`. Akun uji memerlukan hak CREATE/DROP DATABASE.
Pengujian tidak membaca `.env` dan tidak mengubah `kelompok_db`.
