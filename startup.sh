#!/bin/bash
# Switch to www-data user to run the Laravel artisan command
#su www-data -s /bin/bash -c "php /var/www/html/artisan translation:generate-json"

printenv > /etc/environment

set -e

# Ensure the needed storage directories exist in the mounted volume.
if [ ! -d /var/www/html/storage/app/public ]; then
    mkdir -p /var/www/html/storage/app/public
fi

# Remove the existing symlink if it exists (it may be broken now)
if [ -L /var/www/html/public/storage ]; then
    rm /var/www/html/public/storage
fi

# Recreate the storage symlink so that public/storage points to storage/app/public
php artisan storage:link

# Set the proper ownership and permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Optionally, ensure the log file exists:
mkdir -p /var/www/html/storage/logs
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log

# Start cron in the background
service cron start

# Start Apache as www-data in the foreground
exec apache2-foreground
