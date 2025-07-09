FROM php:8.2-apache

# 1. Instala dependencias del sistema y extensiones PHP (todo en un solo RUN para optimizar)
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libzip-dev \
        zlib1g-dev \
        libonig-dev \
        libpq-dev \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Configura Apache para usar /public como raíz
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Copia el proyecto
COPY . /var/www/html/

# 4. Configura permisos (seguridad mejorada)
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    chmod -R 775 /var/www/html/storage/ /var/www/html/bootstrap/cache/

# 5. Puerto expuesto
EXPOSE 80