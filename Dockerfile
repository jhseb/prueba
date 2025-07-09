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

# 2. Establecer document root a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Establecer directorio de trabajo
WORKDIR /var/www/html

# 5. Copiar archivos del proyecto
COPY . .

# 6. Instalar dependencias de Symfony
RUN composer install --no-dev --optimize-autoloader

# 7. Permisos para carpeta var (Symfony)
RUN chown -R www-data:www-data var && chmod -R 775 var

# 8. Exponer puerto 80
EXPOSE 80
