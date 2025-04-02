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

# Установка рабочей директории
WORKDIR /var/www/html

# Создание необходимых директорий
RUN mkdir -p storage/framework/cache && \
    mkdir -p storage/framework/sessions && \
    mkdir -p storage/framework/views && \
    mkdir -p storage/logs && \
    mkdir -p bootstrap/cache

# Копирование файлов проекта
COPY . .

# Установка зависимостей
RUN composer install --no-interaction --no-progress --prefer-dist

# Установка прав
RUN chown -R www-data:www-data /var/www/html

# Генерация ключа приложения
RUN php artisan key:generate --force

# Создание символической ссылки для storage
RUN php artisan storage:link

# Очистка кэша
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan cache:clear

# Переключение на пользователя www-data
USER www-data

# Открываем порт
EXPOSE 8000

# Запуск приложения
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
