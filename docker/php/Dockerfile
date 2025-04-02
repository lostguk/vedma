FROM php:8.3-cli

# Установка минимально необходимых расширений PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install zip pdo_mysql intl exif \
    && rm -rf /var/lib/apt/lists/*

# Рабочая директория
WORKDIR /app

# Копируем весь проект
COPY . .

# Установка прав
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage

# Переключение на пользователя www-data
USER www-data

# Открываем порт
EXPOSE 8000

# Запуск приложения
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
