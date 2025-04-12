FROM php:8.1-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy app source code
COPY ./src /var/www/html/

# Set permissions during build (base)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

COPY apache-config.conf /etc/apache2/conf-available/custom.conf
RUN a2enconf custom

# Add entrypoint to fix permissions at runtime for volume
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

EXPOSE 80
