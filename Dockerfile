FROM php:8.2-apache

# Copia todo el proyecto (incluyendo vendor/)
COPY . /var/www/html/

# Opcional: Si necesitas extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring zip

# Configura Apache para usar la carpeta pública
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permisos para vendor/ (opcional, si hay errores)
RUN chmod -R 755 /var/www/html/vendor/

# Puerto expuesto
EXPOSE 80