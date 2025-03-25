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

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o projeto Laravel para dentro do container
COPY . .

# Instala as dependências do Laravel
RUN composer install --no-interaction --optimize-autoloader

# Ajusta permissões de pastas necessárias
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

# Comando de inicialização do container
CMD ["sh", "-c", "cp .env.example .env && php artisan key:generate && php artisan migrate --force && php artisan db:seed || true && php-fpm"]
