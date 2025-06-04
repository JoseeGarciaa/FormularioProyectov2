# Usa la imagen oficial de PHP con Apache
FROM php:8.0-apache

# Instalar la extensión mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copia los archivos del proyecto al directorio raíz del servidor web
COPY . /var/www/html/

# Ajustar permisos de los archivos
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Copia la configuración de Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Reiniciar Apache para aplicar la nueva configuración
RUN service apache2 restart

# Exponer el puerto 80 para el servidor web
EXPOSE 80
