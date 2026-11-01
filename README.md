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
| `m1` | Anggota | *(TBD)* | `-` | `members/m1/` |
| `m2` | Anggota | *(TBD)* | `-` | `members/m2/` |
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
