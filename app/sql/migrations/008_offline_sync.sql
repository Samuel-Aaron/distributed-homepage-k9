-- Jalankan SATU KALI pada database pusat yang sudah ada, sebelum deploy T-08.
-- Gunakan akun administrator; akun aplikasi tidak memerlukan hak ALTER.
-- Baris lama tetap memiliki event_id NULL dan tidak dihapus/diubah.
USE kelompok_db;

ALTER TABLE kunjungan
    ADD COLUMN event_id CHAR(32) NULL AFTER id,
    ADD UNIQUE KEY uq_kunjungan_event (event_id);
