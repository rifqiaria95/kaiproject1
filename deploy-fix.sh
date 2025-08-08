#!/bin/bash

# Script untuk memperbaiki masalah deployment Laravel
echo "Memperbaiki masalah deployment..."

# Hapus cache config
php artisan config:clear
php artisan cache:clear

# Reset migration status (opsional - hanya jika database bisa di-reset)
# php artisan migrate:reset

# Jalankan migration dengan force flag untuk mengatasi tabel yang sudah ada
php artisan migrate --force

# Clear cache lagi setelah migration
php artisan config:clear
php artisan cache:clear

echo "Deployment fix selesai!"
