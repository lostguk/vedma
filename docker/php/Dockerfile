# ===============================
# Этап 1: Composer dependencies
# ===============================
FROM composer:2.8 AS composer

WORKDIR /app

# Копируем только файлы composer для кэширования
COPY composer.json composer.lock ./

# Устанавливаем зависимости без dev пакетов
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs \
    --prefer-dist

# ===============================
# Этап 2: Production image
# ===============================
FROM php:8.3-fpm-alpine AS production

LABEL maintainer="VedmaShop"
LABEL version="1.0"

# Устанавливаем системные зависимости
RUN apk add --no-cache \
    supervisor \
    nginx \
    mysql-client \
    zip \
    unzip \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    imagemagick-dev \
    && rm -rf /var/cache/apk/*

# Устанавливаем PHP расширения
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        zip \
        gd \
        intl \
        exif \
        sockets \
        opcache \
    && pecl install redis imagemagick \
    && docker-php-ext-enable redis imagick

# Создаем непривилегированного пользователя
RUN addgroup -g 1000 -S appgroup \
    && adduser -u 1000 -S appuser -G appgroup -h /var/www -s /bin/sh

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем composer зависимости из первого этапа
COPY --from=composer --chown=appuser:appgroup /app/vendor ./vendor

# Копируем код приложения
COPY --chown=appuser:appgroup . .

# Копируем зависимости composer и генерируем autoloader
COPY --from=composer --chown=appuser:appgroup /app/composer.json /app/composer.lock ./
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --no-scripts \
    && composer clear-cache

# Создаем необходимые директории и устанавливаем права
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R appuser:appgroup \
        storage \
        bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache

# Копируем конфигурационные файлы
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Создаем директории для nginx и устанавливаем права
RUN mkdir -p \
        /var/run/nginx \
        /var/log/nginx \
        /var/log/supervisor \
    && chown -R appuser:appgroup \
        /var/run/nginx \
        /var/log/nginx \
        /var/log/supervisor \
        /etc/nginx \
        /etc/supervisor

# Переключаемся на непривилегированного пользователя
USER appuser

# Кэшируем конфигурацию Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Возвращаемся к root для финальных настроек
USER root

# Экспонируем порт
EXPOSE 80

# Проверка здоровья
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:80/health || exit 1

# Запускаем supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]