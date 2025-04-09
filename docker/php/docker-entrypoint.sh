#!/bin/bash
set -e

# Проверяем, что .env существует и доступен
if [ ! -f .env ]; then
    echo ".env file not found!"
    exit 1
fi

# Устанавливаем зависимости Composer, если vendor не существует
if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --no-progress --prefer-dist
fi

# Генерируем ключ приложения, если он не установлен
if ! php artisan key:generate --no-interaction --force; then
    echo "Failed to generate APP_KEY"
    exit 1
fi

# Ждем пока база поднимется 20 секунд
sleep 20
# Запускаем миграции, если таблицы не существуют
php artisan migrate --no-interaction --force

# Генерируем API документацию с помощью Scribe
echo "Generating API documentation with Scribe..."
php artisan scribe:generate

# Очищаем кэши
php artisan config:clear
php artisan view:clear
php artisan route:clear

composer fresh-seed

echo "Initialization completed successfully!"

echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Запускаем основную команду
exec "$@"