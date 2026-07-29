#!/bin/sh

mkdir -p /var/www/html/storage/logs \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/database

touch /var/www/html/storage/logs/laravel.log
touch /var/www/html/database/database.sqlite

chown -R www-data:www-data /var/www/html/storage /var/www/html/database /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/database /var/www/html/bootstrap/cache

php artisan config:clear || true
php artisan cache:clear || true
php artisan migrate --force || true
php artisan db:seed --force || true

chown -R www-data:www-data /var/www/html/storage /var/www/html/database /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/database /var/www/html/bootstrap/cache

php-fpm -D

PORT_TO_USE="${PORT:-8080}"
sed -i "s/8080/${PORT_TO_USE}/g" /etc/nginx/sites-available/default

echo "Starting Nginx on port ${PORT_TO_USE}..."
exec nginx -g "daemon off;"
