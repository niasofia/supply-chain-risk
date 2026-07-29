#!/bin/sh

mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite

chmod -R 777 /var/www/html/storage /var/www/html/database /var/www/html/bootstrap/cache

php artisan config:clear || true
php artisan cache:clear || true
php artisan migrate --force || true
php artisan db:seed --force || true

php-fpm -D

PORT_TO_USE="${PORT:-8080}"
sed -i "s/8080/${PORT_TO_USE}/g" /etc/nginx/sites-available/default

echo "Starting Nginx on port ${PORT_TO_USE}..."
exec nginx -g "daemon off;"
