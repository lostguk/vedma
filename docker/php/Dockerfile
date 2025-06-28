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

# Создаём непривилегированного пользователя
RUN addgroup --system appgroup && adduser --system --ingroup appgroup --uid 1000 appuser

# Рабочая директория проекта
WORKDIR /var/www/html

# Копируем php.ini
COPY php.ini /usr/local/etc/php/conf.d/security.ini

# Копируем docker-entrypoint.sh
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Копируем исходники (если ты это делаешь здесь)
COPY . .

# Настройка прав после копирования проекта
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
    && touch .env \
    && chown -R appuser:appgroup /var/www/html \
    && chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/.env

# Переходим на безопасного пользователя
USER appuser

# Запуск
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]