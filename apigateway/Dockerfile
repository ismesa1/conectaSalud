FROM php:8.2-cli

# Dependencias de Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos a la imagen
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-interaction --prefer-dist

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

# Ejecutar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
