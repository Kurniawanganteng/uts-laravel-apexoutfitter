@echo off
title Apex Outfitter POS - UTS Pemrograman Web
echo ========================================================
echo   MEMULAI SISTEM APEX OUTFITTER POS & OUTDOOR GEAR (UTS)
echo ========================================================
echo.
echo Pastikan MySQL di XAMPP Control Panel sudah aktif (Running).
echo.
cd /d "%~dp0"
echo Membuka server di: http://127.0.0.1:8000
echo.
echo Akun Login Demo:
echo   - Owner/Admin : admin@mail.com    (password: password123)
echo   - Staff Kasir : staff@mail.com    (password: password123)
echo   - Customer    : customer@mail.com (password: password123) [Jack Kurniawan]
echo.
echo Tekan Ctrl + C untuk menghentikan server.
echo ========================================================
php artisan serve --host=127.0.0.1 --port=8000
pause
