<?php
/**
 * app/lib/env.php — Native .env Parser & Helper
 *
 * Membaca file .env tanpa ketergantungan library luar/Composer.
 * Menyimpan konfigurasi ke putenv(), $_ENV, dan $_SERVER.
 */

if (!function_exists('load_env')) {
    function load_env(?string $path = null): void
    {
        static $loaded = false;
        if ($loaded) {
            return;
        }

        if ($path === null) {
            // Default: membaca .env di root folder personal/
            $path = dirname(__DIR__, 2) . '/.env';
        }

        if (!file_exists($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Lewati baris kosong atau baris komentar (#)
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Pisahkan KEY dan VALUE pada tanda '=' pertama
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $val = trim($parts[1]);

            // Hapus tanda kutip tunggal atau ganda jika ada
            if ((str_starts_with($val, '"') && str_ends_with($val, '"')) ||
                (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
                $val = substr($val, 1, -1);
            }

            // Simpan ke environment PHP
            putenv("{$key}={$val}");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }

        $loaded = true;
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        // Pastikan file .env sudah termuat
        load_env();

        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        // Konversi tipe nilai boolean dan null
        $lower = strtolower((string)$value);
        if ($lower === 'true') {
            return true;
        }
        if ($lower === 'false') {
            return false;
        }
        if ($lower === 'null') {
            return null;
        }

        return $value;
    }
}

// Inisialisasi otomatis saat file ini di-require
load_env();
