#!/bin/bash
set -e

# Устанавливаем зависимости Composer, если vendor не существует
if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --no-progress --prefer-dist
fi

# Генерируем ключ приложения, если он не установлен
php artisan key:generate --no-interaction --force

# Запускаем миграции, если таблицы не существуют
php artisan migrate --no-interaction --force

# Генерируем API документацию с помощью Scribe
echo "Generating API documentation with Scribe..."
php artisan scribe:generate

# Очищаем кэши
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo "Initialization completed successfully!"

# Запускаем основную команду
exec "$@"
