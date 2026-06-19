# Use PHP 8.2 CLI
FROM php:8.2-cli

# Install system dependencies & PostgreSQL driver
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Copy your application code
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 🔥 CREATE ALL REQUIRED DIRECTORIES AND SET PERMISSIONS
RUN mkdir -p /var/www/html/bootstrap/cache \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/testing \
    /var/www/html/storage/logs && \
    chown -R www-data:www-data /var/www/html/bootstrap /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache /var/www/html/storage

# Install PHP dependencies (production mode)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Final permission check
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run storage:link, migrations, clear caches, and start server
CMD php artisan storage:link && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=$PORT