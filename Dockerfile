FROM php:8.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configure Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Create directory and set permissions
RUN mkdir -p /var/www/api.e-loa.id
COPY src/ /var/www/api.e-loa.id/
RUN chown -R www-data:www-data /var/www/api.e-loa.id

# Start Nginx and PHP-FPM
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]