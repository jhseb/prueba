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

# 2. Habilitar mod_rewrite para Symfony
RUN a2enmod rewrite

# 3. Establecer DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Establecer directorio de trabajo y copiar archivos
WORKDIR /var/www/html
COPY . .

# 5. Copiar Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Instalar dependencias Symfony en modo producción
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative && \
    php bin/console cache:clear --env=prod && \
    php bin/console cache:warmup --env=prod

# 7. Asegurar permisos para la carpeta var/
RUN mkdir -p var && chown -R www-data:www-data var && chmod -R 775 var

# 8. Asegurarse de tener .htaccess en /public (lo añade si no existe)
RUN if [ ! -f public/.htaccess ]; then echo '<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^ index.php [QSA,L]\n</IfModule>' > public/.htaccess; fi

# 9. Exponer el puerto
EXPOSE 80

# 10. Iniciar Apache en primer plano
CMD ["apache2-foreground"]
