# =========================
# Frontend build
# =========================
FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# =========================
# PHP + Nginx
# =========================
FROM php:8.3-fpm

WORKDIR /app

# System deps
RUN apt-get update && apt-get install -y \
    curl git zip unzip nginx supervisor \
    libpq-dev libzip-dev libicu-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP extensions
RUN docker-php-ext-install \
    pdo pdo_pgsql mbstring zip bcmath intl opcache

# Copy app
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Frontend build
COPY --from=frontend /app/public/build ./public/build

# Nginx config
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

# Copy startup script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]

