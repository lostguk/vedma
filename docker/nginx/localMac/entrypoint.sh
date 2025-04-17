#!/bin/sh
set -e
echo "APP_ENV is $APP_ENV"

if [ "$APP_ENV" = "local" ]; then
  echo "Setting permissions for dev environment..."
  chown -R nginx:nginx /var/www/html/public
  chmod -R 755 /var/www/html/public
fi

exec "$@"