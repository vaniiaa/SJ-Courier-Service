FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl gd

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies (exclude dev for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set file permissions
RUN chmod -R 755 storage bootstrap/cache \
    && mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data storage bootstrap/cache

# Set environment variable & expose port
ENV PORT=8080
EXPOSE 8080

# Start Laravel using artisan serve (not recommended for production)
CMD ["sh", "-c", "php artisan config:clear && php artisan view:clear && php artisan cache:clear && php artisan serve --host=0.0.0.0 --port=8080"]
