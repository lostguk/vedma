# syntax=docker/dockerfile:1
# -----------------------------------------------------------------------------
# Stage 1 — install Composer dependencies without dev packages
FROM composer:2.7 AS vendor
WORKDIR /app

# Copy the bare minimum required to resolve dependencies
COPY composer.json composer.lock ./

# Install PHP dependencies for production
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --apcu-autoloader

# -----------------------------------------------------------------------------
# Stage 2 — build runtime image
FROM php:8.3-fpm-alpine AS app

# Install system dependencies & PHP extensions (only what the framework needs)
RUN apk add --no-cache \
        icu-dev \
        oniguruma-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        gmp-dev \
        zlib-dev \
        bash \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        intl \
        opcache \
        gd \
        bcmath \
        gmp \
        exif \
        xml \
    # Remove build-time deps
    && apk del git bash

# PHP production tweaks
COPY --chown=www-data:www-data docker/prod/php/conf/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Create non-root user for running PHP-FPM
ARG UID=1000
RUN adduser -D -u ${UID} -s /bin/sh laravel \
    && mkdir -p /var/www/html \
    && chown -R laravel:laravel /var/www/html

WORKDIR /var/www/html

# Copy application source code
COPY --chown=laravel:laravel . .

# Copy the vendor directory from the Composer stage
COPY --from=vendor /app/vendor ./vendor

# Laravel optimisations
RUN php artisan config:cache --no-ansi \
    && php artisan route:cache --no-ansi \
    && php artisan view:cache --no-ansi

USER laravel

EXPOSE 9000

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]