FROM php:8.3-fpm

# Установка минимально необходимых расширений PHP и утилит
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    # Добавляем инструменты для сети и почты
    iputils-ping \
    dnsutils \
    telnet \
    openssl \
    libssl-dev \
    # Добавляем библиотеки для GD
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql intl exif gd \
    # Устанавливаем расширения для SMTP
    && docker-php-ext-install sockets \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория (оставляем, т.к. она нужна для php-fpm)
WORKDIR /var/www/html

# Создаем скрипт для инициализации проекта, если он нужен
# Но лучше делать инициализацию через docker-compose.yml или вручную
# COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
# RUN chmod +x /usr/local/bin/docker-entrypoint.sh
# ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm"]
