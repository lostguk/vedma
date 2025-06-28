FROM php:8.3-fpm

# Устанавливаем необходимые зависимости
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    zip \
    unzip \
    iputils-ping \
    dnsutils \
    telnet \
    openssl \
    libzip-dev \
    libicu-dev \
    libssl-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        zip \
        pdo_mysql \
        intl \
        exif \
        gd \
        sockets \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создаём пользователя без root-доступа
RUN addgroup --system appgroup && adduser --system --ingroup appgroup appuser

# Рабочая директория и права
WORKDIR /var/www/html
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
    && chown -R appuser:appgroup /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Безопасные php.ini настройки (опционально подключи volume или копируй сюда)
COPY php.ini /usr/local/etc/php/conf.d/security.ini

# Docker entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

USER appuser

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]