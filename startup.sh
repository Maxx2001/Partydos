#!/bin/bash
# Switch to www-data user to run the Laravel artisan command
#su www-data -s /bin/bash -c "php /var/www/html/artisan translation:generate-json"

printenv > /etc/environment

# Create storage symlink if it does not exist
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi


# Fix permissions for storage if needed
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
