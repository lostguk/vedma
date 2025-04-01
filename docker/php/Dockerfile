FROM php:8.3-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    nodejs \
    npm \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    webp

# Настройка и установка PHP расширений
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www/html

# Установка прав на директорию
RUN chown -R www-data:www-data /var/www/html && \
    mkdir -p /var/www/.npm && \
    chown -R www-data:www-data /var/www/.npm

USER www-data

# Генерация Swagger документации при запуске контейнера
CMD composer install && php artisan l5-swagger:generate && php-fpm
