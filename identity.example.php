<?php
/**
 * identity.example.php — Contoh template identitas server lokal.
 *
 * Salin file ini menjadi 'identity.php' di root project lokal Anda:
 * Windows: copy identity.example.php identity.php
 *
 * File 'identity.php' dilindungi .gitignore dan TIDAK IKUT ter-commit ke Git.
 * Setiap anggota mengisi nilainya sendiri sesuai route yang ditetapkan.
 */

if (!defined('MEMBER_ROUTE')) {
    define('MEMBER_ROUTE', 'm0');               // Pilihan: 'm0' (ketua), 'm1', 'm2', 'm3' (anggota)
    define('MEMBER_NAMA',  'Samuel Aaron');     // Ganti dengan Nama Lengkap Anda
    define('MEMBER_NIM',   '103012580024');     // Ganti dengan NIM Anda
}
