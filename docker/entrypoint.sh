#!/bin/sh

if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

PORT="${PORT:-8080}"
echo "Starting Laravel server on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
