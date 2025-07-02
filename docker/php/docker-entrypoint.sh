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

# Ждем пока база поднимется
echo "Waiting for database..."
until php artisan db:show --database=mysql > /dev/null 2>&1; do
    echo "Database not ready, waiting..."
    sleep 5
done

# Запускаем миграции, если таблицы не существуют
echo "Running migrations..."
php artisan migrate --no-interaction --force

# Запускаем сиды только если база пустая (проверяем наличие пользователей)
USER_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ]; then
    echo "Database is empty, seeding..."
    php artisan db:seed --force
else
    echo "Database already has data, skipping seeding"
fi

# Генерируем API документацию с помощью Scribe
echo "Generating API documentation with Scribe..."
php artisan scribe:generate

# Очищаем кэши
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo "Initialization completed successfully!"

echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Запускаем основную команду
exec "$@"