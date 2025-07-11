# Stage 1: Build dependencies
FROM composer:2.8 AS composer-build

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies without dev packages
RUN composer install \
    --no-interaction \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --optimize-autoloader

# Copy application code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Stage 2: Build frontend assets
FROM node:20-alpine AS frontend-build

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install npm dependencies
RUN npm ci --only=production

# Copy application files
COPY . .

# Build assets
RUN npm run build

# Stage 3: Production image
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    nginx \
    supervisor \
    curl \
    nano

# Install PHP extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    gd \
    zip \
    intl \
    mbstring \
    pcntl \
    bcmath \
    opcache \
    exif

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Configure PHP-FPM
COPY docker/production/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/production/php/php.ini /usr/local/etc/php/php.ini

# Create non-root user
RUN addgroup -g 1000 -S laravel && \
    adduser -u 1000 -S laravel -G laravel

# Set working directory
WORKDIR /var/www/html

# Copy application from build stages
COPY --from=composer-build --chown=laravel:laravel /app /var/www/html
COPY --from=frontend-build --chown=laravel:laravel /app/public/build /var/www/html/public/build

# Copy nginx and supervisor configs
COPY docker/production/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/production/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/storage/logs \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/app/public \
    /var/www/html/bootstrap/cache \
    && chown -R laravel:laravel /var/www/html/storage \
    && chown -R laravel:laravel /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Expose port
EXPOSE 80

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]