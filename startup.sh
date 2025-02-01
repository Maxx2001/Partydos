#!/bin/bash
printenv > /etc/environment
set -e

mkdir -p /var/www/html/storage/app/event/banners

chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

# Optionally, ensure the log file exists:
mkdir -p /var/www/html/storage/logs
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log

# Start cron in the background
service cron start

# Start Apache as www-data in the foreground
exec apache2-foreground
