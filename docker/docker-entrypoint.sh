#!/bin/sh
set -e

cd /var/www/html

mkdir -p \
    storage/app/public/tmp \
    storage/app/public/livewire-tmp \
    storage/app/private/livewire-tmp \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

if [ ! -e public/storage ]; then
    ln -sfn ../storage/app/public public/storage
fi

# На production storage/app монтируется с хоста (deploy user) — приводим права для php-fpm (www).
chown -R www:www storage 2>/dev/null || true
chmod -R 775 storage 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisord.conf
