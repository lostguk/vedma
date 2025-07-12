# Многоэтапная сборка для продакшна
FROM composer:2.7 AS composer

# Устанавливаем расширения PHP в контейнере composer
RUN apk add --no-cache icu-dev \
    && docker-php-ext-install intl exif

# Копируем файлы composer
COPY composer.json composer.lock ./

# Устанавливаем зависимости для продакшна
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Копируем весь код и генерируем автозагрузчик
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

# Основной образ для продакшна
FROM php:8.3-fpm-alpine AS production

# Метаданные
LABEL maintainer="Vedma Shop Team"
LABEL version="1.0"
LABEL description="Laravel Vedma Shop Production Image"

# Установка системных зависимостей
RUN apk add --no-cache \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client \
    nginx \
    supervisor \
    icu-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache \
    && rm -rf /var/cache/apk/*

# Создание пользователя www
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Создание необходимых директорий
RUN mkdir -p /var/www/html \
    /var/log/supervisor \
    /var/log/nginx \
    /var/cache/nginx \
    /var/run \
    && chown -R www:www /var/www/html \
    && chmod -R 755 /var/www/html

# Копирование конфигураций
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Копирование приложения
COPY --from=composer --chown=www:www /app /var/www/html

# Установка правильных разрешений
RUN chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache \
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