#!/bin/bash
printenv > /etc/environment
set -e

chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

mkdir -p /var/www/html/storage/app/event/banners


# Remove any existing (possibly broken) symlink in public/img/event/banners
if [ -L /var/www/html/public/img/event/banners ]; then
    rm /var/www/html/public/img/event/banners
fi

mkdir -p /var/www/html/public/img/event

ln -s /var/www/html/storage/app/event/banners /var/www/html/public/img/event/banners

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
