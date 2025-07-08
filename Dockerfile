# Stage 1: Composer build
FROM composer:2 as builder

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Stage 2: PHP + FPM + Caddy
FROM php:8.2-fpm

# Install system & PHP extensions
RUN apt-get update && apt-get install -y \
    curl git zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl gd \
    && curl -fsSL https://get.caddyserver.com | bash -s personal

# Set working directory
WORKDIR /var/www

# Copy app from builder
COPY --from=builder /app /var/www

# Set permission
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 storage bootstrap/cache

# Copy Caddy config
COPY Caddyfile /etc/caddy/Caddyfile

# Expose port 8080 (Railway expects this)
EXPOSE 8080

# Start Caddy server
CMD ["caddy", "run", "--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
