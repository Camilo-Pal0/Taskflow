# Imagen base con PHP + Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Configurar DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# Copiar proyecto
COPY . /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Generar cachés de Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer el puerto (Render lo detecta automáticamente)
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
