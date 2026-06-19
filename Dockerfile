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

# Create cache & storage directories and set permissions
RUN mkdir -p /var/www/html/bootstrap/cache /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/bootstrap /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache /var/www/html/storage

# Install PHP dependencies (production mode)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Double-check permissions after install
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🔥 NEW: Run migrations AND storage:link automatically on container start
CMD php artisan storage:link && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT