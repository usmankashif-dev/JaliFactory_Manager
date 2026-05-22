#!/bin/sh

set -e

echo "Fixing permissions..."
chown -R www-data:www-data /app || true
chmod -R 777 storage bootstrap/cache

echo "Waiting for database..."
sleep 5

echo "Running migrations..."
php artisan migrate --force || true

echo "Clearing cache..."
php artisan optimize:clear || true

echo "Starting PHP..."
php-fpm -D

echo "Starting Nginx..."
nginx -g "daemon off;"
