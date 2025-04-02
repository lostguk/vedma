FROM php:8.3-cli

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
    webp \
    && rm -rf /var/lib/apt/lists/*

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
    intl \
    && docker-php-source delete

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка кэширования Composer
ENV COMPOSER_HOME=/var/www/.composer
ENV COMPOSER_CACHE_DIR=/var/www/.composer/cache
ENV NPM_CONFIG_CACHE=/var/www/.npm

# Установка рабочей директории
WORKDIR /var/www/html

# Копирование файлов проекта
COPY . .

# Установка прав на директории
RUN mkdir -p /var/www/.composer/cache \
    && mkdir -p /var/www/.npm \
    && chown -R www-data:www-data /var/www

# Установка зависимостей
RUN composer install --no-interaction --no-progress --prefer-dist

# Переключение на пользователя www-data
USER www-data

# Открываем порт
EXPOSE 8000

# Запуск приложения
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
