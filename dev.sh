#!/bin/bash

# Универсальный скрипт для разработки
# Использование: ./dev.sh <команда>
# Доступные команды: reset-db, docs, test, filament-cache, lint, ide-helper

case "$1" in
    docker-up)
        docker-compose -f docker-compose.local.yml up -d --build
        ;;
    reset-db)
        docker-compose -f docker-compose.local.yml exec php php artisan migrate:fresh --seed
        ;;
    docs)
        docker-compose -f docker-compose.local.yml exec php php artisan scribe:generate
        ;;
    test)
        docker-compose -f docker-compose.local.yml exec php php artisan test
        ;;
    filament-cache)
        docker-compose -f docker-compose.local.yml exec php php artisan filament:clear-cache
        ;;
    artisan)
        shift
        docker-compose -f docker-compose.local.yml exec php php artisan "$@"
        ;;
    lint)
        docker-compose -f docker-compose.local.yml exec php ./vendor/bin/pint
        ;;
    ide-helper)
        docker-compose -f docker-compose.local.yml exec php php artisan ide-helper:models --nowrite && \
        docker-compose -f docker-compose.local.yml exec php php artisan ide-helper:meta
        ;;
    *)
        echo "Usage: $0 {docker-up|reset-db|docs|test|filament-cache|lint|ide-helper|artisan}"
        ;;
esac
