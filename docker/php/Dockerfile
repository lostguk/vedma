FROM php:8.3-fpm

# Установка минимально необходимых расширений PHP
# (Добавим unzip и zip для работы с зависимостями, если их нет в fpm образе)
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install zip pdo_mysql intl exif \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка переменных окружения
ENV APP_ENV=production
ENV APP_DEBUG=true
ENV COMPOSER_ALLOW_SUPERUSER=1

# Рабочая директория
WORKDIR /app

# Копируем файлы композера
COPY composer.* ./

# Установка зависимостей (без автозагрузчика на этом этапе)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress

# Копируем весь проект
COPY . .

# Генерируем автозагрузчик ДО выполнения artisan команд
RUN composer dump-autoload --optimize

# Оптимизация Laravel для продакшена (кэширует config, routes, views)
# RUN php artisan optimize # Temporarily disabled for debugging startup

# Финальные настройки прав и владельцев (chown должен быть последним перед USER www-data)
# RUN mkdir -p storage/framework/{sessions,views,cache} \ # Temporarily disabled for debugging startup
#     && chmod -R 775 storage bootstrap/cache \ # Temporarily disabled for debugging startup
#     && chown -R www-data:www-data /app # Temporarily disabled for debugging startup

# Переключение на пользователя www-data
USER www-data

# CMD закомментирован, т.к. Railway использует свой механизм запуска
# CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
