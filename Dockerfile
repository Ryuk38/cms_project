FROM php:8.1-apache
WORKDIR /var/www/html
COPY . .
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
EXPOSE 80
CMD ["apache2-foreground"]