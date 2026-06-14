#!/bin/sh
set -e

cd /var/www/html

mkdir -p storage/app/public

if [ ! -e public/storage ]; then
    ln -sfn ../storage/app/public public/storage
fi

chown -R www:www storage/app/public 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisord.conf
