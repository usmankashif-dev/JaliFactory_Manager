# =========================
# Frontend Build Stage
# =========================
FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

RUN npm run build


# =========================
# PHP Stage
# =========================
FROM php:8.3-fpm

WORKDIR /app

# System deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl git bash zip unzip nginx supervisor \
    libpq-dev libzip-dev libicu-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP extensions
RUN docker-php-ext-install \
    pdo pdo_pgsql mbstring zip bcmath opcache intl

# Copy app
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Frontend build
COPY --from=frontend /app/public/build ./public/build

# Permissions
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Nginx configs
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 8080

# =========================
# START SCRIPT (FIXED PROPERLY)
# =========================
RUN printf "%s\n" \
"#!/bin/bash" \
"set -e" \
"" \
"echo 'Waiting for DB...'" \
"sleep 5" \
"" \
"echo 'Clearing cache...'" \
"php artisan optimize:clear || true" \
"" \
"echo 'Running migrations...'" \
"php artisan migrate --force || true" \
"" \
"echo 'Caching...'" \
"php artisan config:cache" \
"php artisan route:cache" \
"php artisan view:cache" \
"" \
"echo 'Starting services...'" \
"php-fpm -D" \
"nginx -g 'daemon off;'" \
> /app/start.sh && chmod +x /app/start.sh

CMD ["/app/start.sh"]