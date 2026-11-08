# 📋 Progress Tracker & Pembagian Tugas (Kelompok 9)

Dokumen ini digunakan oleh seluruh anggota tim untuk melacak progres pengerjaan fitur bersama, mengklaim tugas (*take a task*), dan mendokumentasikan kontribusi masing-masing.

---

## 👥 Status Anggota & Halaman Personal

Setiap anggota wajib menyelesaikan dan melakukan *push* halaman personalnya ke folder masing-masing:

| Route | Posisi | Nama Anggota | NIM | Status Halaman Personal |
|---|---|---|---|---|
| `m0` | Ketua | Samuel Aaron | `103012580024` | ✅ Selesai (`members/m0/`) |
| `m1` | Anggota | *(Isi Nama)* | *(Isi NIM)* | ⏳ Belum Mulai (`members/m1/`) |
| `m2` | Anggota | *(Isi Nama)* | *(Isi NIM)* | ⏳ Belum Mulai (`members/m2/`) |
| `m3` | Anggota | *(Isi Nama)* | *(Isi NIM)* | ⏳ Belum Mulai (`members/m3/`) |

---

## 🚀 Shared Task Pool (Fitur Aplikasi Bersama)

Siapa saja yang memiliki waktu dan kemampuan dapat mengambil tugas di bawah ini. Cukup buat branch baru dan ubah status tugas menjadi `🔄 Sedang Dikerjakan`.

| ID | Modul / Fitur | Status | PIC | Branch Terkait |
|---|---|---|---|---|
| **T-01** | **Fondasi Struktur Folder, Pure `.env` & Koneksi PDO Fail-Safe**<br>Menyiapkan skema database `schema.sql`, pembaca `.env`, dan koneksi PDO yang tidak crash jika DB mati. | ✅ Selesai | Samuel Aaron | `main` |
| **T-02** | **Pencatatan Otomatis (`auto`) & Hash Privasi IP**<br>Mencatat setiap hit ke tabel `kunjungan` dengan `route_id`, `target_member`, `is_failover`, dan hash SHA-256 IP. | ✅ Selesai (PR #1) | Samuel Aaron | `feature/t02-auto-log` |
| **T-03** | **Form Check-in (Tulis) & Validasi Ganda**<br>Form nama & pesan pengunjung, token CSRF, validasi di sisi JavaScript (klien) dan PHP (server). | ⏳ Siap Diambil | *(Terbuka)* | `feature/t03-form-checkin` |
| **T-04** | **Daftar Riwayat Kunjungan (Baca) & Paginasi**<br>Tabel responsif menampilkan data riwayat check-in terbaru dengan pembagian halaman (10 data/halaman). | ⏳ Siap Diambil | *(Terbuka)* | `feature/t04-display-pagination` |
| **T-05** | **Pencarian & Filter Multi-Server**<br>Fitur pencarian kata kunci nama/pesan serta filter data berdasarkan server asal (`m0`, `m1`, `m2`, `m3`) atau status failover. | ⏳ Siap Diambil | *(Terbuka)* | `feature/t05-search-filter` |
| **T-06** | **Dashboard Analitik & JSON API**<br>Grafik/ringkasan total kunjungan, pembagian beban server, dan endpoint JSON `app/api/stats.php`. | ⏳ Siap Diambil | *(Terbuka)* | `feature/t06-stats-dashboard` |
| **T-07** | **Banner Server Pintar & Switcher Failover**<br>Banner `served_by.php` dan node switcher untuk simulasi failover saat ada server yang mati. | ✅ Selesai | Samuel Aaron | `main` |
| **T-08** | **Offline Buffer & Auto-Synchronize**<br>Menyimpan kunjungan ke `app/storage/pending_sync.json` saat DB pusat mati, lalu otomatis sinkronisasi (*bulk insert*) saat DB hidup kembali. | ⏳ Siap Diambil | *(Terbuka)* | `feature/t08-offline-sync` |
| **T-09** | **Health Check Lintas Jaringan & Auto-Failback**<br>Endpoint `app/api/health.php` dan pengecekan status node teman (via Tailscale/Virtual LAN) untuk Failover & Failback otomatis. | ⏳ Siap Diambil | *(Terbuka)* | `feature/t09-health-failback` |
| **T-10** | **Security Testing & Hardening**<br>Pengujian penetrasi input `<script>alert(1)</script>` (XSS) dan `' OR '1'='1` (SQL Injection). | ⏳ Siap Diambil | *(Terbuka)* | `feature/t10-security-test` |

---

## 🛠️ Cara Mengklaim & Memperbarui Tugas

1. **Pilih Tugas:** Pilih ID tugas di atas yang statusnya masih `⏳ Siap Diambil`.
2. **Buat Branch:** Buat branch lokal sesuai nama branch terkait, misalnya:
   ```bash
   git checkout -b feature/t03-form-checkin
   ```
3. **Klaim di Dokumen Ini:** Ubah status di tabel menjadi `🔄 Sedang Dikerjakan` dan isi nama Anda di kolom **PIC**.
4. **Koding & Commit:** Kerjakan fitur tersebut, lalu commit:
   ```bash
   git add .
   git commit -m "feat(T-03): implementasi form check-in dan validasi"
   git push origin feature/t03-form-checkin
   ```
5. **Buat Pull Request (PR):** Buka PR di GitHub menuju branch `main`. Setelah di-review dan di-merge, ubah status tugas menjadi `✅ Selesai`.
