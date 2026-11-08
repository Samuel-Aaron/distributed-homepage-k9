-- ============================================================
-- sql/schema.sql — Aplikasi Bersama: Penghitung Kunjungan Terdistribusi
-- Kompatibel MariaDB (XAMPP) dan MySQL 8 (GCP): UTF8MB4 & SQL Standar.
-- ============================================================

CREATE DATABASE IF NOT EXISTS kelompok_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE kelompok_db;

-- 1. Tabel Anggota (Daftar route tetap kelompok)
CREATE TABLE IF NOT EXISTS anggota (
    route_id VARCHAR(10) PRIMARY KEY, -- 'm0', 'm1', 'm2', 'm3'
    nim VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    status_node ENUM('online', 'offline') DEFAULT 'online'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Log Kunjungan & Check-in
CREATE TABLE IF NOT EXISTS kunjungan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_id VARCHAR(10) NOT NULL,            -- Server fisik yang melayani (m0..m3)
    target_member VARCHAR(10) NOT NULL,       -- Halaman anggota yang dikunjungi (m0..m3)
    is_failover TINYINT(1) DEFAULT 0,         -- 1 jika dilayani sebagai server cadangan
    jenis ENUM('auto', 'checkin') DEFAULT 'auto',
    nama VARCHAR(100) NULL,                   -- Nama pengunjung (jika check-in)
    pesan VARCHAR(255) NULL,                  -- Pesan pengunjung (jika check-in)
    ip_hash CHAR(64) NOT NULL,                -- Hash SHA-256 IP (privasi)
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_route (route_id),
    INDEX idx_target (target_member),
    INDEX idx_created (created_at),
    CONSTRAINT fk_kunjungan_server FOREIGN KEY (route_id) REFERENCES anggota(route_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data awal anggota kelompok (NIM & nama anggota m1-m3 dapat disesuaikan)
INSERT INTO anggota (route_id, nim, nama) VALUES
  ('m0', '103012580024', 'Samuel Aaron'),
  ('m1', '103012580055', 'Marthin Tambunan'),
  ('m2', '103012400193', 'Akbar Saputra Hardian'),
  ('m3', '-', 'Anggota 3')
ON DUPLICATE KEY UPDATE nim = VALUES(nim), nama = VALUES(nama);

-- ============================================================
-- User database lokal dibuat dengan hak minimum (jalankan di phpMyAdmin / MySQL CLI)
-- ============================================================
-- CREATE USER IF NOT EXISTS 'app_local'@'localhost' IDENTIFIED BY 'password_lokal';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON kelompok_db.* TO 'app_local'@'localhost';
-- FLUSH PRIVILEGES;
