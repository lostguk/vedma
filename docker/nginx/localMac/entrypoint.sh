#!/bin/sh
set -e

# Функция для логирования
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

log "Starting nginx entrypoint for Mac environment..."
log "APP_ENV is ${APP_ENV:-local}"

if [ "${APP_ENV:-local}" = "local" ]; then
    log "Setting permissions for Mac development environment..."

    # Создаем директории если их нет
    mkdir -p /var/www/html/public
    mkdir -p /var/www/html/storage/app/public

    # На Mac у нас пользователь appuser (UID: 1000)
    # Устанавливаем права для appuser вместо nginx
    if command -v appuser >/dev/null 2>&1; then
        log "Setting ownership for appuser..."
        chown -R appuser:appgroup /var/www/html/public 2>/dev/null || true
        chown -R appuser:appgroup /var/www/html/storage/app/public 2>/dev/null || true
    else
        log "appuser not found, using nginx user..."
        chown -R nginx:nginx /var/www/html/public 2>/dev/null || true
        chown -R nginx:nginx /var/www/html/storage/app/public 2>/dev/null || true
    fi

    # Устанавливаем права доступа
    chmod -R 755 /var/www/html/public 2>/dev/null || true
    chmod -R 755 /var/www/html/storage/app/public 2>/dev/null || true

    log "Permissions set successfully"
else
    log "Production environment detected, skipping permission changes"
fi

# Проверяем конфигурацию nginx
log "Testing nginx configuration..."
if nginx -t; then
    log "Nginx configuration is valid"
else
    log "ERROR: Nginx configuration is invalid!"
    exit 1
fi

log "Starting nginx..."
exec "$@"
