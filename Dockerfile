# =========================
# Frontend Build Stage
# =========================
FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

ENV APP_URL=https://jalifactory-manager.onrender.com
RUN npm run build


# =========================
# PHP Production Stage
# =========================
FROM php:8.3-fpm

WORKDIR /app

# System deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl git bash zip unzip nginx supervisor \
    postgresql-client default-mysql-client \
    libxml2-dev libzip-dev libonig-dev libicu-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# PHP extensions
RUN docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql mbstring zip bcmath \
    opcache fileinfo dom intl ctype

# PHP config
RUN { \
    echo 'memory_limit=512M'; \
    echo 'upload_max_filesize=100M'; \
    echo 'post_max_size=100M'; \
    echo 'max_execution_time=600'; \
    echo 'display_errors=Off'; \
    echo 'log_errors=On'; \
} > /usr/local/etc/php/conf.d/app.ini

# Opcache
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.interned_strings_buffer=16'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Copy composer files first
COPY composer.json composer.lock* ./

# Install dependencies (NO artisan scripts yet)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --no-scripts

# Copy full app
COPY . .

# NOW run artisan safely
RUN php artisan package:discover --ansi || true

# Copy frontend build
COPY --from=frontend /app/public/build ./public/build

# Storage folders
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chown -R www-data:www-data /app && \
    chmod -R 775 storage bootstrap/cache

# Nginx config
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 8080

# Healthcheck
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8080 || exit 1

# =========================
# START SCRIPT (FIXED)
# =========================
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Clearing Laravel caches..."\n\
php artisan config:clear || true\n\
php artisan cache:clear || true\n\
php artisan optimize:clear || true\n\
\n\
echo "Running migrations..."\n\
php artisan migrate --force\n\
\n\
echo "Caching app..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "Starting services..."\n\
php-fpm -D\n\
nginx -g "daemon off;"\n\
' > /app/start.sh && chmod +x /app/start.sh

CMD ["/app/start.sh"]