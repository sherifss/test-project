# Use PHP 8 with FPM
FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev && \
    docker-php-ext-install pdo_mysql mysqli gd && \
    docker-php-ext-enable pdo_mysql mysqli gd

# Install Composer 2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure Nginx
RUN mkdir -p /var/www
COPY ./docker/nginx/nginx.conf /etc/nginx/sites-available/default

# Set the working directory
WORKDIR /var/www

COPY . .

RUN composer install --no-scripts --no-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose ports
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
