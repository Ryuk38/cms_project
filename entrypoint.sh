#!/bin/bash
# Fix permissions for volume-mounted files
if [ -d "/var/www/html" ]; then
  chown -R www-data:www-data /var/www/html
  chmod -R 755 /var/www/html
fi
exec "$@"