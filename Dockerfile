FROM php:8.2-apache

# 1. Instala dependencias del sistema para las extensiones PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \       # Necesario para 'zip'
    zlib1g-dev \       # Necesario para 'zip'
    libonig-dev \      # Necesario para 'mbstring'
    libpq-dev \        # Necesario para 'pdo_mysql' (alternativa: libmysqlclient-dev)
    && rm -rf /var/lib/apt/lists/*

# 2. Instala las extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring zip

# 3. Configura Apache para usar /public como raíz
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copia el proyecto (incluyendo vendor/)
COPY . /var/www/html/

# 5. Opcional: Establece permisos (ajusta según tu proyecto)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage  # Si usas Laravel

# 6. Puerto expuesto
EXPOSE 80