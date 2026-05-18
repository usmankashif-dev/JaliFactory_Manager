# Build stage for Node dependencies and Vite build
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Build stage for SSR (optional, if you're using Inertia SSR)
FROM node:22-alpine AS ssr-build

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build:ssr || true

# PHP stage
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    bash \
    postgresql-dev \
    mysql-client \
    oniguruma-dev \
    libzip-dev \
    zip \
    unzip \
    composer \
    nginx

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    mbstring \
    zip \
    bcmath \
    opcache

# Configure PHP
RUN { \
    echo 'memory_limit = 256M'; \
    echo 'upload_max_filesize = 50M'; \
    echo 'post_max_size = 50M'; \
    echo 'max_execution_time = 600'; \
    } > /usr/local/etc/php/conf.d/app.ini

# Setup work directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock* ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy application files
COPY . .

# Copy built frontend from frontend stage
COPY --from=frontend /app/public/build ./public/build
COPY --from=ssr-build /app/bootstrap/ssr ./bootstrap/ssr || true

# Create necessary directories
RUN mkdir -p storage/logs storage/framework/{sessions,views,cache}
RUN chmod -R 775 storage bootstrap/cache

# Copy nginx configuration
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

# Expose port
EXPOSE 8080

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Generate app key if not exists\n\
if [ -z "$APP_KEY" ]; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Run migrations\n\
php artisan migrate --force\n\
\n\
# Start services\n\
php-fpm &\n\
nginx -g "daemon off;"\n\
' > /app/start.sh && chmod +x /app/start.sh

CMD ["/app/start.sh"]
