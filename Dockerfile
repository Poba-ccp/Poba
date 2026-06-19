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

# ============================================================
# 🔥 FIX: Create the cache directory AND set permissions 
#        BEFORE running composer install
# ============================================================
RUN mkdir -p /var/www/html/bootstrap/cache /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/bootstrap /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache /var/www/html/storage

# Install PHP dependencies (production mode)
# This will now run 'php artisan package:discover' successfully
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Double-check permissions after install (just to be safe)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the dynamic port from Render and start Laravel
CMD php artisan serve --host=0.0.0.0 --port=$PORT