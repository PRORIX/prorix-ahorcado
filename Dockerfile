FROM php:8.2-apache
WORKDIR /var/www/html
RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
 && a2enmod rewrite
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage