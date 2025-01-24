#!/bin/bash
# Switch to www-data user to run the Laravel artisan command
#su www-data -s /bin/bash -c "php /var/www/html/artisan translation:generate-json"

printenv > /etc/environment

# Start cron in the background
service cron start

# Start Apache as www-data in the foreground
exec apache2-foreground
