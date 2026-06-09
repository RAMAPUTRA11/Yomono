#!/usr/bin/env bash
# Keluar jika ada error
set -o errexit

echo "Executing build script..."
composer install --no-dev --optimize-autoloader

# Clear dan optimalkan cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan database migration otomatis secara paksa
php artisan migrate --force