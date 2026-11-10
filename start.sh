#!/usr/bin/env bash
set -e

echo "==> Ensuring required storage directories exist..."
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache || true

echo "==> Creating storage symlink..."
php artisan storage:link || true

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Running database seeders..."
php artisan db:seed --force || true

echo "==> Caching routes and views..."
php artisan route:cache || true
php artisan view:cache || true

# Determine web server strategy
if [ -f /assets/nginx.template.conf ] && [ -f /assets/transform-config.pl ]; then
    echo "==> Starting Nginx & PHP-FPM..."
    perl /assets/transform-config.pl /assets/nginx.template.conf /nginx.conf
    php-fpm -y /assets/php-fpm.conf &
    exec nginx -c /nginx.conf
else
    echo "==> Starting PHP built-in server on port ${PORT:-8000}..."
    exec php -S 0.0.0.0:${PORT:-8000} -t public vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
fi
