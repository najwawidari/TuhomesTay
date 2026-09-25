#!/bin/sh
set -e

# Cache config (opsional, tapi direkomendasikan)
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Migrate database (kalau environment variable DB sudah di-set)
if [ -n "$DATABASE_URL" ] || [ -n "$DB_HOST" ]; then
    php artisan migrate --force || true
fi

# Start Laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
