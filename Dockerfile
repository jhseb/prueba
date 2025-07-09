FROM php:8.2-apache

# 1. Instalar extensiones necesarias para Symfony
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Habilitar módulos de Apache
RUN a2enmod rewrite

# 3. Establecer document root a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copiar el resto del proyecto
WORKDIR /var/www/html
COPY . .

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Instalar dependencias Symfony
RUN composer install --no-dev --optimize-autoloader

# 7. Crear carpeta var si no existe, y aplicar permisos
RUN mkdir -p var && chown -R www-data:www-data var && chmod -R 775 var

# 8. Exponer el puerto 80
EXPOSE 80

# 9. Comando por defecto
CMD ["apache2-foreground"]
