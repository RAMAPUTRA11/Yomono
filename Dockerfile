FROM php:8.2-fpm-alphine

# Install system dependencies & PHP extensions
RUN apk add --no-cache nginx supervisor mariadb-client shadow \
    && docker-php-ext-install pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app
COPY . .

# Install dependencies laravel
RUN composer install --no-dev --optimize-autoloader

# Setup Nginx & Permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
COPY ./nginx.conf /etc/nginx/nginx.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]