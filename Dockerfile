FROM php:8.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring exif pcntl bcmath gd

# Configure Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Configure PHP-FPM to use unix socket
RUN mkdir -p /var/run && \
    mkdir -p /var/log/php-fpm && \
    sed -i 's/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm.sock/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/;listen.mode = 0660/listen.mode = 0666/g' /usr/local/etc/php-fpm.d/www.conf

# Create directory and set permissions
RUN mkdir -p /var/www/be-game-bahasaku
COPY src/ /var/www/be-game-bahasaku/
RUN chown -R www-data:www-data /var/www/be-game-bahasaku

# Start Nginx and PHP-FPM
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]