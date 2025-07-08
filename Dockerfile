# Stage 1: Composer build (pakai php:8.2-cli)
FROM php:8.2-cli as builder

# Install PHP extensions needed to run Composer in Laravel
RUN apt-get update && apt-get install -y \
    unzip zip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Runtime (php-fpm + Caddy)
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl git zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl gd \
    && curl -fsSL https://get.caddyserver.com | bash -s personal

WORKDIR /var/www

# Copy project from builder
COPY --from=builder /app /var/www

# Set permission
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 storage bootstrap/cache

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

EXPOSE 8080

CMD ["caddy", "run", "--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
