# Sistem Perhitungan Suara (Laravel 13)

Ini sudah proyek Laravel LENGKAP (ada `artisan`, `composer.json`, `.env`, dll).
Tidak perlu `composer create-project` lagi.

## Prasyarat

- PHP 8.3 atau lebih baru, dengan ekstensi: sqlite3 / pdo_sqlite, mbstring, openssl, ctype, json, fileinfo, tokenizer, xml
- Composer

## Cara menjalankan (di terminal, di dalam folder ini)

    composer install
    php artisan migrate --seed
    php artisan serve

Buka http://127.0.0.1:8000
Login awal: admin@bawaslu.test / password (segera ganti lewat menu "Akun")

## Jika ada error

- "could not find driver": aktifkan `extension=pdo_sqlite` dan `extension=sqlite3` di php.ini.
- "No application encryption key": jalankan `php artisan key:generate`.
- Database error / mau mulai ulang dari nol: `php artisan migrate:fresh --seed`.
- Jika `.env` hilang: `copy .env.example .env` lalu `php artisan key:generate`.

## Memakai MySQL (opsional)

Ubah bagian DB\_ di `.env` (DB_CONNECTION=mysql, DB_DATABASE, DB_USERNAME, DB_PASSWORD),
buat databasenya, lalu `php artisan migrate --seed`.

## Peran

- admin: kelola kandidat, petugas, hapus data TPS, plus semua fitur petugas
- petugas: input/edit suara TPS, lihat ringkasan, unduh CSV
