FROM php:8.3-fpm

# Устанавливаем необходимые зависимости и gosu
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
    gosu \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        zip \
        pdo_mysql \
        intl \
        exif \
        gd \
        sockets \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создаём непривилегированного пользователя
ARG UID=1000
ARG GID=1000

RUN addgroup --system --gid $GID appgroup && \
    adduser --system --uid $UID --ingroup appgroup --home /home/appuser --shell /bin/bash appuser && \
    mkdir -p /home/appuser && \
    chown appuser:appgroup /home/appuser

# Рабочая директория проекта
WORKDIR /var/www/html

# Копируем конфигурационные файлы
COPY php.ini /usr/local/etc/php/conf.d/security.ini
COPY fpm-pool.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Копируем docker-entrypoint.sh
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Создаем необходимые директории и файлы с правильными правами
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
    && touch .env \
    && chown -R appuser:appgroup /var/www/html \
    && chmod -R ug+rwX /var/www/html

# Переходим на безопасного пользователя для запуска
ENV HOME=/home/appuser

# Создаем точку монтирования для tmp
RUN mkdir -p /tmp/php && chown appuser:appgroup /tmp/php

# Expose порт
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD php-fpm -t || exit 1

# Запуск через entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm", "--nodaemonize"]
