# Build stage for Node dependencies and Vite build
FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

# Set APP_URL for asset generation with HTTPS
ENV APP_URL=https://jalifactory-manager.onrender.com
RUN npm run build

# PHP stage - Production ready Laravel Dockerfile
FROM php:8.3-fpm

# Set working directory
WORKDIR /app

# Install system dependencies - all at once for better layer caching
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    bash \
    zip \
    unzip \
    postgresql-client \
    default-mysql-client \
    nginx \
    supervisor \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install all PHP extensions at once
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    bcmath \
    opcache \
    fileinfo \
    dom \
    intl \
    ctype

# Configure PHP production settings
RUN { \
    echo 'memory_limit = 512M'; \
    echo 'upload_max_filesize = 100M'; \
    echo 'post_max_size = 100M'; \
    echo 'max_execution_time = 600'; \
    echo 'default_charset = utf-8'; \
    echo 'display_errors = Off'; \
    echo 'log_errors = On'; \
    } > /usr/local/etc/php/conf.d/app.ini

# Configure opcache for production
RUN { \
    echo 'opcache.enable = 1'; \
    echo 'opcache.revalidate_freq = 0'; \
    echo 'opcache.validate_timestamps = 0'; \
    echo 'opcache.fast_shutdown = 1'; \
    echo 'opcache.interned_strings_buffer = 16'; \
    echo 'opcache.max_accelerated_files = 20000'; \
    echo 'opcache.memory_consumption = 256'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Copy Composer files
COPY composer.json composer.lock* ./

# Install PHP dependencies without running scripts (artisan doesn't exist yet)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# Copy application files
COPY . .

# Run Laravel package discovery now that artisan exists
RUN php artisan package:discover --ansi || true

# Copy built assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Clear and optimize for production
RUN php artisan config:cache --ansi 2>/dev/null || true && \
    php artisan route:cache --ansi 2>/dev/null || true && \
    php artisan view:cache --ansi 2>/dev/null || true

# Create necessary directories and set permissions
RUN mkdir -p storage/logs storage/framework/{sessions,views,cache} bootstrap/cache && \
    chown -R www-data:www-data /app && \
    chmod -R 755 storage bootstrap/cache

# Copy nginx configuration
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

# Expose port
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Starting application..."\n\
\n\
# Generate app key if not exists\n\
if [ -z "$APP_KEY" ]; then\n\
    echo "Generating app key..."\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Run migrations\n\
echo "Running database migrations..."\n\
php artisan migrate --force 2>/dev/null || true\n\
\n\
# Clear caches\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "Application started successfully"\n\
\n\
# Start PHP-FPM and Nginx\n\
php-fpm &\n\
nginx -g "daemon off;"\n\
' > /app/start.sh && chmod +x /app/start.sh

CMD ["/app/start.sh"]
