# Многоэтапная сборка для продакшна
FROM composer:2.7 AS composer

# Устанавливаем расширения PHP в контейнере composer
# Важно: phpoffice/phpspreadsheet требует ext-gd уже на этапе composer install.
RUN apk add --no-cache \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd intl exif

# Копируем файлы composer
COPY composer.json composer.lock ./

# Устанавливаем зависимости для продакшна
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Копируем весь код и генерируем автозагрузчик
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

# Сборка frontend assets для Laravel/Vite.
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY vite.config.js ./
COPY resources ./resources
COPY --from=composer /app/vendor ./vendor
RUN npm run build

# Основной образ для продакшна
FROM php:8.3-fpm-alpine AS production

# Метаданные
LABEL maintainer="Vedma Shop Team"
LABEL version="1.0"
LABEL description="Laravel Vedma Shop Production Image"

# Установка системных зависимостей и сборочных инструментов для pecl redis
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    mysql-client \
    nginx \
    supervisor \
    icu-dev \
    autoconf \
    g++ \
    make \
    gcc \
    libc-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache \
    && apk del autoconf g++ make gcc libc-dev \
    && rm -rf /var/cache/apk/*

# Создание пользователя www
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Создание необходимых директорий
RUN mkdir -p /var/www/html \
    /var/log/supervisor \
    /var/log/nginx \
    /var/cache/nginx/client_temp \
    /var/run \
    && chown -R www:www /var/www/html /var/cache/nginx \
    && chmod -R 755 /var/www/html \
    && chmod -R 750 /var/cache/nginx

# Копирование конфигураций
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/php-production.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Копирование приложения
COPY --from=composer --chown=www:www /app /var/www/html
COPY --from=assets --chown=www:www /app/public/build /var/www/html/public/build

# Установка правильных разрешений
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/bootstrap/cache \
    && chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Переключение на пользователя www
USER www

# Экспорт порта
EXPOSE 8080

# Переключение обратно на root для supervisor
USER root

# Точка входа
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

# Healthcheck
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# DEV-образ на базе production c установленным composer
FROM production AS development

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php-development.ini /usr/local/etc/php/conf.d/zzz-development.ini
ENV COMPOSER_ALLOW_SUPERUSER=1
