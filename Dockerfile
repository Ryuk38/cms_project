FROM php:8.1-apache

# Set working dir
WORKDIR /var/www/html

# Copy source code
COPY . .

# Enable mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Fix 403 Forbidden and set index.php as default
RUN echo "<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    DirectoryIndex index.php index.html\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/custom.conf && \
    a2enconf custom

# Enable mod_rewrite (if needed for .htaccess)
RUN a2enmod rewrite

EXPOSE 80
CMD ["apache2-foreground"]