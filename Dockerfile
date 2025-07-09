FROM php:8.2-apache

# 1. Instala dependencias y extensiones
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libzip-dev \
        zlib1g-dev \
        libonig-dev \
        libpq-dev \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Configura Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Crea directorios necesarios primero (evita errores de permisos)
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

# 4. Copia el proyecto
COPY . /var/www/html/

# 5. Configura permisos (versión segura)
RUN if [ -d "/var/www/html/storage" ]; then \
        chown -R www-data:www-data /var/www/html/storage; \
        chmod -R 775 /var/www/html/storage; \
    fi && \
    if [ -d "/var/www/html/bootstrap/cache" ]; then \
        chown -R www-data:www-data /var/www/html/bootstrap/cache; \
        chmod -R 775 /var/www/html/bootstrap/cache; \
    fi

# 6. Puerto
EXPOSE 80