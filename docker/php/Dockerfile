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
RUN php artisan optimize

# Финальные настройки прав и владельцев (chown должен быть последним перед USER www-data)
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R www-data:www-data /app

# Переключение на пользователя www-data
USER www-data

# Создаем скрипт для инициализации проекта
COPY --chown=www-data:www-data docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
