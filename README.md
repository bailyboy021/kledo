# Kledo REST-API

Kledo adalah sebuah REST-API untuk sistem pengelolaan persetujuan pengeluaran (expense approval) yang dibangun menggunakan Laravel 9. API ini mendukung pengelolaan data transaksi dengan database MySQL menggunakan Eloquent ORM dan dokumentasi API berbasis Swagger (l5-swagger).


## Teknologi yang Digunakan

- **Framework**: Laravel 9 (PHP 8.2)
- **Database**: MySQL
- **ORM**: Eloquent
- **Dokumentasi API**: Swagger melalui l5-swagger

## Instalasi

1. Instal dependensi dengan Composer:

   ```bash
   composer install

2. Salin file .env.example menjadi .env lalu sesuaikan konfigurasi database dan L5_SWAGGER:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kledo
   DB_USERNAME=root
   DB_PASSWORD=

   L5_SWAGGER_GENERATE_ALWAYS=true
   L5_SWAGGER_API_VERSION=1.0.0
   L5_SWAGGER_TITLE="API Documentation"
   L5_SWAGGER_DESCRIPTION="Documentation for Kledo API"
   L5_SWAGGER_SCHEMES=https
   L5_SWAGGER_BASE_PATH=/api

3. Generate kunci aplikasi Laravel:

   ```bash
   php artisan key:generate

4. Migrasi database dan seed data awal:

   ```bash
   php artisan migrate --seed

5. Jalankan server:

   ```bash
   php artisan serve

## Dokumentasi API

Untuk melihat dokumentasi API:
1. Pastikan aplikasi berjalan di server lokal
2. Akses dokumentasi API di URL berikut:

   ```bash
   http://localhost:8000/api/documentation
