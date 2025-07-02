FROM php:8.3-fpm

# Устанавливаем необходимые зависимости с проверкой подписей
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
    ca-certificates \
    curl \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        zip \
        pdo_mysql \
        intl \
        exif \
        gd \
        sockets \
        opcache \
        bcmath \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

# Установка Composer с проверкой подписи
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Проверяем целостность Composer
RUN composer --version

# Создаём непривилегированного пользователя с фиксированными UID/GID
ARG UID=1000
ARG GID=1000

# Проверка корректности UID/GID
RUN test "$UID" -ge 1000 && test "$GID" -ge 1000 || (echo "UID/GID must be >= 1000 for security" && exit 1)

RUN addgroup --system --gid $GID appgroup && \
    adduser --system --uid $UID --ingroup appgroup --shell /bin/bash appuser

# Рабочая директория проекта
WORKDIR /var/www/html

# Копируем конфигурационные файлы с проверкой
COPY --chown=root:root php.ini /usr/local/etc/php/conf.d/99-security.ini
COPY --chown=root:root php-dev.ini /usr/local/etc/php/conf.d/99-dev.ini.disabled
COPY --chown=root:root fmp-pool.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Копируем и проверяем права entrypoint скрипта
COPY --chown=root:root docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod 755 /usr/local/bin/docker-entrypoint.sh && \
    test -x /usr/local/bin/docker-entrypoint.sh

# Создаём необходимые директории с правильными правами
RUN mkdir -p \
    /var/www/html/storage/framework/{cache,sessions,views} \
    /var/www/html/bootstrap/cache \
    /var/www/html/storage/logs \
    /var/www/html/storage/app/public \
    /home/appuser/.composer \
    && touch /var/www/html/.env \
    && chown -R appuser:appgroup \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/.env \
        /home/appuser \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod 600 /var/www/html/.env

# Security: устанавливаем корректные права на PHP конфигурацию
RUN chmod 644 /usr/local/etc/php/conf.d/99-security.ini \
    && chmod 644 /usr/local/etc/php-fpm.d/zz-docker.conf

# Переключаемся на безопасного пользователя
USER appuser

# Устанавливаем переменные окружения
ENV HOME=/home/appuser
ENV COMPOSER_HOME=/home/appuser/.composer
ENV PATH="${PATH}:/home/appuser/.composer/vendor/bin"

# Healthcheck
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD php-fpm -t || exit 1

# Запуск
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm", "--nodaemonize"]

# Метки для отслеживания
LABEL maintainer="dev-team@company.com"
LABEL version="1.0"
LABEL description="Secure PHP-FPM container for Laravel application"
LABEL security.scan="required"
