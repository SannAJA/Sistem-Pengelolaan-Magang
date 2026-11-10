#!/usr/bin/env bash
set -e

echo "==> Creating storage symlink..."
php artisan storage:link || true

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Running database seeders..."
php artisan db:seed --force || true

echo "==> Caching routes and views..."
php artisan route:cache
php artisan view:cache

# Determine web server strategy
if [ -f /assets/nginx.template.conf ] && [ -f /assets/transform-config.pl ]; then
    echo "==> Starting Nginx & PHP-FPM..."
    [ -e /app/storage ] && chmod -R ugo+w /app/storage || true
    [ -e /app/bootstrap/cache ] && chmod -R ugo+w /app/bootstrap/cache || true
    perl /assets/transform-config.pl /assets/nginx.template.conf /nginx.conf
    php-fpm -y /assets/php-fpm.conf &
    exec nginx -c /nginx.conf
else
    echo "==> Starting PHP built-in server on port ${PORT:-8000}..."
    exec php -S 0.0.0.0:${PORT:-8000} -t public vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
fi

