#!/bin/bash
# Switch to www-data user to run the Laravel artisan command
su www-data -s /bin/bash -c "php /var/www/html/artisan translation:generate-json"

# Start Apache as www-data in the foreground
exec apache2-foreground
