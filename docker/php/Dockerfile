# docker/php/Dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache


CMD ["php-fpm"]
