#!/bin/bash
set -e

# Функция для логирования
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

# Проверяем, что .env существует и доступен
if [ ! -f .env ]; then
    log "ERROR: .env file not found!"
    exit 1
fi

# Проверяем права доступа к важным директориям
log "Setting up permissions..."

# Убеждаемся, что appuser владеет необходимыми директориями
chown -R appuser:appgroup /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Проверяем доступность MySQL
log "Waiting for MySQL connection..."
for i in {1..30}; do
    if gosu appuser php artisan migrate:status --database=mysql >/dev/null 2>&1; then
        log "MySQL connection established"
        break
    fi
    if [ $i -eq 30 ]; then
        log "ERROR: MySQL connection timeout"
        exit 1
    fi
    sleep 2
done

# Устанавливаем зависимости Composer, если vendor не существует
if [ ! -d vendor ]; then
    log "Installing Composer dependencies..."
    gosu appuser composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader
fi

# Проверяем, совпадает ли uid/gid владельца /var/www/html с текущим пользователем
HOST_UID=$(stat -c "%u" /var/www/html)
HOST_GID=$(stat -c "%g" /var/www/html)

log "Configuring Git safe.directory for /var/www/html..."
gosu appuser git config --global --add safe.directory /var/www/html

# Генерируем ключ приложения, если он не установлен
# log "Generating application key..."
# if ! gosu appuser php artisan key:generate --no-interaction --force; then
#     log "ERROR: Failed to generate APP_KEY"
#     exit 1
# fi

# Запускаем миграции
log "Running migrations..."
gosu appuser php artisan migrate --no-interaction --force

# Генерируем API документацию с помощью Scribe
log "Generating API documentation with Scribe..."
gosu appuser php artisan scribe:generate

# Create symbolic link for storage
log "Creating storage symbolic link..."
gosu appuser php artisan storage:link

# Очищаем кэши
log "Clearing caches..."
gosu appuser php artisan config:clear
gosu appuser php artisan view:clear
gosu appuser php artisan route:clear

# Запускаем сиды только в dev окружении
if [ "${APP_ENV:-production}" = "local" ] || [ "${APP_ENV:-production}" = "dev" ]; then
    log "Running seeders..."
    gosu appuser composer fresh-seed
fi

# Create tmp directory for media library if it doesn't exist
log "Creating tmp directory for media library..."
mkdir -p /var/www/html/storage/app/public/tmp
chown -R appuser:appgroup /var/www/html/storage/app/public/tmp
chmod -R 775 /var/www/html/storage/app/public/tmp

log "Initialization completed successfully!"

# Запускаем основную команду от имени root для php-fpm
if [ "$1" = "php-fpm" ]; then
    log "Starting PHP-FPM..."
    exec "$@"
else
    # Для остальных команд используем gosu
    log "Executing command as appuser: $*"
    exec gosu appuser "$@"
fi
