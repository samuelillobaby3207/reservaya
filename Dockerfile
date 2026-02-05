# 1. Uso una imagen oficial de PHP con Apache
FROM php:8.0-apache

# 2. Instalo las extensiones para que funcione MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 3. Copio todos tus archivos dentro del contenedor
COPY . /var/www/html/

# 4. Ajusto los permisos del servidor
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 5. Abro el puerto 80 para la web
EXPOSE 80
