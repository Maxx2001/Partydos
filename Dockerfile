#
# Composer
#
FROM composer:2.6 as vendor

WORKDIR /app

COPY . .
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --ignore-platform-reqs \
    --prefer-dist

RUN composer dump-autoload


#
# Node frontend
#
FROM node:18.0 as frontend

WORKDIR /app

COPY . .
COPY --from=vendor /app/vendor/ ./vendor/

RUN npm install
RUN npm run build

#
# Application build
#
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y libzip-dev unzip libpng-dev libjpeg-dev libfreetype6-dev libmcrypt-dev libmagickwand-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install zip gd exif pdo pdo_mysql

# Install and enable the Imagick PHP extension
RUN pecl install imagick && docker-php-ext-enable imagick

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy Frontend build
COPY --from=frontend /app/node_modules/ ./node_modules/
COPY --from=frontend /app/public/ ./public/

# Copy Composer dependencies
COPY --from=vendor /app/vendor/ ./vendor/

# Copy the Laravel application files to the container
COPY . /var/www/html

# Change ownership to the web server user (www-data) and adjust permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy the startup script into the image
COPY startup.sh /usr/local/bin/startup.sh

COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

RUN chown -R www-data:www-data /usr/local/etc/php/conf.d/uploads.ini

# Give execution rights on the startup script
RUN chmod +x /usr/local/bin/startup.sh

# Set the Apache DocumentRoot to your public directory
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Expose port 80 (HTTP)
EXPOSE 80

# Start the Apache web server
CMD ["/usr/local/bin/startup.sh"]
