@echo off
title Setup Apex Outfitter di Laptop Baru
echo ========================================================
echo   SETUP OTOMATIS APEX OUTFITTER (LAPTOP BARU)
echo ========================================================
echo.
echo 1. Pastikan XAMPP (Apache dan MySQL) sudah berstatus RUNNING!
echo 2. Pastikan Anda sudah membuat database kosong bernama 'inventory_uts' di phpMyAdmin.
echo.
pause

cd /d "%~dp0"

echo.
echo [1/4] Menginstall paket dependensi (vendor)...
call composer install --no-interaction

echo.
echo [2/4] Menyiapkan file konfigurasi environment (.env)...
if not exist .env (
    copy .env.example .env
    call php artisan key:generate
)

echo.
echo [3/4] Membangun 11 tabel & mengisi data demo otomatis (Migration + Seeding)...
call php artisan migrate:fresh --seed --force

echo.
echo [4/4] Menghubungkan tautan penyimpanan (storage:link)...
call php artisan storage:link

echo.
echo ========================================================
echo   SELAMAT! SETUP BERHASIL 100%!
echo   Web dan seluruh datanya sudah siap digunakan.
echo   Tinggal klik file 'JALANKAN_APLIKASI.bat' untuk mulai.
echo ========================================================
echo.
pause
