# ==============================================================================
# Multi-Stage Dockerfile for BharatTravel Holidays ERP & Marketplace
# PHP 8.4-FPM, Node.js 22, Nginx, Barryvdh DomPDF, Redis, MySQL
# ==============================================================================

# ------------------------------------------------------------------------------
# Stage 1: Build Frontend Assets (Vite + Tailwind CSS v4 + Alpine.js + Lucide)
# ------------------------------------------------------------------------------
FROM node:22-alpine AS frontend
WORKDIR /app

# Cache package dependencies
COPY package.json package-lock.json ./
RUN npm ci --prefer-offline --no-audit

# Copy asset source files
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

# Build production assets into public/build
RUN npm run build

# ------------------------------------------------------------------------------
# Stage 2: Install PHP Composer Dependencies
# ------------------------------------------------------------------------------
FROM composer:2 AS composer-deps
WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-plugins \
    --prefer-dist \
    --optimize-autoloader

# ------------------------------------------------------------------------------
# Stage 3: Final Production & Development Application Image
# ------------------------------------------------------------------------------
FROM php:8.4-fpm-alpine AS app

LABEL maintainer="BharatTravel Holidays Dev Team" \
      description="BharatTravel ERP & CRM - PHP 8.4 FPM Runtime"

# Install system libraries, utilities, and fonts for Barryvdh DomPDF
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    icu-libs \
    fontconfig \
    ttf-freefont \
    ttf-dejavu \
    su-exec \
    shadow \
    mysql-client

# Install PHP extensions using official docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    zip \
    gd \
    bcmath \
    intl \
    opcache \
    redis \
    pcntl

# Install Composer CLI into final image
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Copy custom PHP & OPcache configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copy application source code
COPY . .

# Copy built vendor dependencies and frontend assets from build stages
COPY --from=composer-deps /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]
