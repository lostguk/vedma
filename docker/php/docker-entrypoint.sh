#!/bin/bash
set -euo pipefail

# Security: проверяем переменные окружения
if [[ -z "${APP_ENV:-}" ]]; then
    echo "ERROR: APP_ENV environment variable is not set"
    exit 1
fi

# Проверяем, что .env существует и доступен
if [ ! -f .env ]; then
    echo "ERROR: .env file not found!"
    exit 1
fi

# Проверяем права на .env файл
ENV_PERMS=$(stat -c "%a" .env)
if [[ "$ENV_PERMS" != "600" && "$ENV_PERMS" != "644" ]]; then
    echo "WARNING: .env file has insecure permissions: $ENV_PERMS"
    chmod 600 .env
fi

# Устанавливаем зависимости Composer, если vendor не существует
if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    if [[ "${APP_ENV}" == "production" ]]; then
        composer install --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader
    else
        composer install --no-interaction --no-progress --prefer-dist
    fi
fi

# Проверяем, совпадает ли uid/gid владельца /var/www/html с текущим пользователем
HOST_UID=$(stat -c "%u" /var/www/html)
HOST_GID=$(stat -c "%g" /var/www/html)
CUR_UID=$(id -u)
CUR_GID=$(id -g)

if [ "$HOST_UID" != "$CUR_UID" ] || [ "$HOST_GID" != "$CUR_GID" ]; then
    echo "Configuring Git safe.directory for /var/www/html due to UID/GID mismatch..."
    git config --global --add safe.directory /var/www/html
fi

# Генерируем ключ приложения, если он не установлен
echo "Checking application key..."
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --no-interaction --force
fi

# Ждем пока база поднимется - более безопасная проверка
echo "Waiting for database connection..."
for i in {1..30}; do
    if php artisan migrate:status --no-interaction >/dev/null 2>&1; then
        echo "Database connection established"
        break
    fi
    if [ $i -eq 30 ]; then
        echo "ERROR: Could not connect to database after 30 attempts"
        exit 1
    fi
    echo "Attempt $i/30: Waiting for database..."
    sleep 2
done

# Безопасные миграции
echo "Running database migrations..."
php artisan migrate --no-interaction --force

# Генерируем API документацию с помощью Scribe
if [[ "${APP_ENV}" != "production" ]]; then
    echo "Generating API documentation with Scribe..."
    php artisan scribe:generate --no-interaction || echo "WARNING: Failed to generate API docs"
fi

# Create symbolic link for storage
echo "Creating storage symbolic link..."
php artisan storage:link --no-interaction || echo "WARNING: Storage link already exists"

# Очищаем кэши
echo "Clearing caches..."
php artisan config:clear --no-interaction
php artisan view:clear --no-interaction
php artisan route:clear --no-interaction

# Seeding only for non-production
if [[ "${APP_ENV}" != "production" ]]; then
    echo "Running database seeders..."
    # Используем стандартную команду вместо кастомной
    php artisan db:seed --no-interaction --force || echo "WARNING: Seeding failed or already completed"
fi

echo "Creating tmp directory for media library..."
mkdir -p /var/www/html/storage/app/public/tmp
chown -R appuser:appgroup /var/www/html/storage/app/public/tmp
chmod -R 775 /var/www/html/storage/app/public/tmp

# Настройка прав доступа
echo "Setting secure permissions..."
chown -R appuser:appgroup /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Безопасность: убираем ненужные права
find /var/www/html -type f -name "*.php" -exec chmod 644 {} \;
find /var/www/html -type d -exec chmod 755 {} \;

echo "Initialization completed successfully!"

# Запускаем основную команду
exec "$@"
