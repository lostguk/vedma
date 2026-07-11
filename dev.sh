#!/bin/bash

# Laravel Sail + Production Docker Management Script
# Простой и понятный скрипт для управления окружениями

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Логирование
log() {
    echo -e "${GREEN}[$(date +'%H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" >&2
    exit 1
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

# Проверка, что .env файл существует
check_env() {
    if [ ! -f .env ]; then
        error ".env файл не найден! Скопируйте .env.example в .env"
    fi
}

# Проверка Docker
check_docker() {
    if ! command -v docker &> /dev/null; then
        error "Docker не установлен!"
    fi

    if ! docker info &> /dev/null; then
        error "Docker daemon не запущен!"
    fi
}

# ===== ЛОКАЛЬНАЯ РАЗРАБОТКА (Sail) =====

sail_up() {
    check_env
    check_docker

    log "Запуск Laravel Sail (разработка)..."

    # Устанавливаем переменные для Sail
    export WWWUSER=$(id -u)
    export WWWGROUP=$(id -g)

    ./vendor/bin/sail up -d

    info "✅ Приложение запущено:"
    info "🌐 Web: http://localhost"
    info "🗄️ MySQL: localhost:3306"
    info "📚 Redis: localhost:6379"
}

sail_down() {
    log "Остановка Laravel Sail..."
    ./vendor/bin/sail down
}

sail_restart() {
    log "Перезапуск Laravel Sail..."
    sail_down
    sail_up
}

sail_build() {
    local no_cache=${1:-}
    if [ "$no_cache" == "--no-cache" ]; then
        log "Полная пересборка Laravel Sail (без кэша)..."
        ./vendor/bin/sail build --no-cache
    else
        log "Сборка Laravel Sail (с кэшем)..."
        ./vendor/bin/sail build
    fi
}

# ===== ПРОДАКШН =====

prod_build() {
    check_env
    check_docker

    local no_cache=${1:-}
    if [ "$no_cache" == "--no-cache" ]; then
        log "Полная пересборка продакшн образа (без кэша)..."
        docker compose -f docker-compose.production.yml build --no-cache
    else
        log "Сборка продакшн образа (с кэшем)..."
        docker compose -f docker-compose.production.yml build
    fi
}

prod_up() {
    check_env
    check_docker

    log "Запуск продакшн окружения (без пересборки образов)..."
    docker compose -f docker-compose.production.yml up -d --no-build

    local port=${APP_PUBLISHED_PORT:-8000}

    info "✅ Продакшн запущен:"
    info "🌐 Web: http://localhost:${port}"
    info "🗄️ MySQL: localhost:3306"
    info "📚 Redis: localhost:6379"

    # Автоматическая очистка старых образов после успешного запуска
    log "Очистка старых образов (старше 3 дней)..."
    docker image prune -a -f --filter "until=72h" 2>/dev/null || warning "Не удалось очистить старые образы"
}

prod_down() {
    log "Остановка продакшн окружения..."
            docker compose -f docker-compose.production.yml down
}

prod_logs() {
    local service=${1:-app}
            docker compose -f docker-compose.production.yml logs -f "$service"
}

prod_backup() {
    check_docker
    log "Создание production-бэкапа..."
    "$SCRIPT_DIR/scripts/backup.sh" backup
}

prod_restore() {
    check_docker
    local target="${1:-latest}"
    local assume_yes="${2:-}"

    if [ "$target" = "--yes" ]; then
        target="latest"
        assume_yes="--yes"
    fi

    warning "⚠️  Восстановление production из бэкапа: $target"
    "$SCRIPT_DIR/scripts/backup.sh" restore "$target" "$assume_yes"
}

prod_backup_list() {
    "$SCRIPT_DIR/scripts/backup.sh" list
}

prod_backup_install_cron() {
    local hour="${1:-3}"
    local minute="${2:-0}"
    log "Установка cron для ежедневного бэкапа (production)..."
    "$SCRIPT_DIR/scripts/backup.sh" install-cron "$hour" "$minute"
}

prod_migrate() {
    check_env
    check_docker

    log "Докатывание миграций (PRODUCTION)..."
    docker compose -f docker-compose.production.yml exec -T app php artisan migrate --force
}

prod_seed() {
    check_env
    check_docker

    log "Запуск production сидов (идемпотентно, без товаров и демо-данных)..."
    docker compose -f docker-compose.production.yml exec -T app sh -lc '
        set -e
        php artisan storage:link || true
        php artisan db:seed --class=ProductionSeeder --force
    '
}

prod_optimize() {
    check_env
    check_docker

    log "Оптимизация Laravel cache (PRODUCTION)..."
    docker compose -f docker-compose.production.yml exec -T app sh -lc '
        set -e
        php artisan storage:link || true
        php artisan filament:assets
        php artisan vendor:publish --tag=livewire:assets --force --no-interaction
        php artisan optimize:clear
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan queue:restart || true
    '
}

prod_health() {
    check_docker

    local port=${APP_PUBLISHED_PORT:-8000}
    log "Проверка health production на http://127.0.0.1:${port}/health..."
    curl -fsS "http://127.0.0.1:${port}/health" >/dev/null
    info "✅ Production health OK"
}

prod_shell() {
    check_docker

    log "Консоль в PRODUCTION app контейнере..."
    docker compose -f docker-compose.production.yml exec app sh
}

prod_diagnose_upload() {
    check_docker

    log "Диагностика загрузки файлов (PRODUCTION)..."
    docker compose -f docker-compose.production.yml exec -T app sh -lc '
        set -e
        echo "=== Конфигурация ==="
        php artisan tinker --execute="
            echo \"APP_URL: \" . config(\"app.url\") . PHP_EOL;
            echo \"APP_ENV: \" . config(\"app.env\") . PHP_EOL;
            echo \"Livewire disk: \" . config(\"livewire.temporary_file_upload.disk\") . PHP_EOL;
            echo \"Livewire dir: \" . config(\"livewire.temporary_file_upload.directory\") . PHP_EOL;
            echo \"Media temp thumbs: \" . (config(\"media-library.generate_thumbnails_for_temporary_uploads\") ? \"on\" : \"off\") . PHP_EOL;
            echo \"Media session affinity: \" . (config(\"media-library.enable_temporary_uploads_session_affinity\") ? \"on\" : \"off\") . PHP_EOL;
            echo \"Queue: \" . config(\"queue.default\") . PHP_EOL;
        "
        echo ""
        echo "=== Права storage (www) ==="
        id www || id
        for dir in storage/app/public/livewire-tmp storage/app/public/tmp storage/app/public; do
            if [ -d "$dir" ]; then
                ls -ld "$dir"
                su -s /bin/sh www -c "touch $dir/.write-test && rm -f $dir/.write-test" && echo "  OK: запись в $dir" || echo "  FAIL: нет записи в $dir"
            else
                echo "  MISSING: $dir"
            fi
        done
        echo ""
        echo "=== Supervisor / queue worker ==="
        supervisorctl status 2>/dev/null || echo "supervisorctl недоступен"
        echo ""
        echo "=== Последние ошибки в laravel.log ==="
        tail -n 30 storage/logs/laravel.log 2>/dev/null | grep -Ei "livewire|upload|media|signed|419|403|413|500" || echo "нет совпадений в последних 30 строках"
        echo ""
        echo "=== Подсказка: внешний reverse proxy ==="
        echo "Убедитесь, что nginx перед Docker передаёт X-Forwarded-* и client_max_body_size >= 64M"
        echo "См. docs/docker/REVERSE_PROXY.md"
    '
}

prod_deploy() {
    prod_build "${1:-}"
    prod_up
    prod_migrate
    prod_optimize
    prod_health
}

# ===== ОБЩИЕ КОМАНДЫ =====

migrate_db() {
    log "Докатывание миграций..."
    ./vendor/bin/sail artisan migrate --force
}

deploy() {
    log "Обновление проекта: composer + миграции + кэш..."
    log "1/4 — Установка зависимостей..."
    ./vendor/bin/sail composer install --no-interaction
    log "2/4 — Докатывание миграций..."
    ./vendor/bin/sail artisan migrate --force
    log "3/4 — Очистка кэшей..."
    ./vendor/bin/sail artisan config:clear
    ./vendor/bin/sail artisan cache:clear
    ./vendor/bin/sail artisan route:clear
    ./vendor/bin/sail artisan view:clear
    log "4/4 — Оптимизация..."
    ./vendor/bin/sail artisan config:cache
    ./vendor/bin/sail artisan route:cache
    info "✅ Проект обновлён!"
}

reset_db() {
    warning "⚠️  ВНИМАНИЕ: Это полностью сбросит базу данных и пересоздаст её с нуля!"
    warning "Если нужно только докатить миграции — используйте: ./dev.sh migrate"
    log "Сброс базы данных..."
    ./vendor/bin/sail artisan migrate:fresh --seed --force
}

run_tests() {
    log "Запуск тестов..."
    ./vendor/bin/sail artisan test
}

generate_docs() {
    log "Генерация API документации..."
    ./vendor/bin/sail artisan scribe:generate
}

clear_cache() {
    log "Очистка кэшей..."
    ./vendor/bin/sail artisan cache:clear
    ./vendor/bin/sail artisan config:clear
    ./vendor/bin/sail artisan route:clear
    ./vendor/bin/sail artisan view:clear
}

format_code() {
    log "Форматирование кода..."
    ./vendor/bin/sail composer pint
}

generate_ide_helpers() {
    log "Генерация IDE helper файлов..."
    ./vendor/bin/sail artisan ide-helper:generate
    ./vendor/bin/sail artisan ide-helper:models --write
    ./vendor/bin/sail artisan ide-helper:meta
}

# Статус контейнеров
status() {
    echo -e "${YELLOW}=== SAIL (Разработка) ===${NC}"
    ./vendor/bin/sail ps 2>/dev/null || echo "Не запущен"

    echo -e "\n${YELLOW}=== PRODUCTION ===${NC}"
    docker compose -f docker-compose.production.yml ps 2>/dev/null || echo "Не запущен"
}

# Логи
logs() {
    local service=${1:-laravel.test}
    ./vendor/bin/sail logs -f "$service"
}

# Консоль
shell() {
    local service=${1:-laravel.test}
    ./vendor/bin/sail exec "$service" bash
}

# Artisan
artisan() {
    ./vendor/bin/sail artisan "$@"
}

# Composer
composer() {
    ./vendor/bin/sail composer "$@"
}

# NPM
npm() {
    ./vendor/bin/sail npm "$@"
}

# Очистка Docker
docker_clean() {
    local aggressive=${1:-}
    if [ -f "./docker/cleanup.sh" ]; then
        log "Используем скрипт очистки..."
        if [ "$aggressive" == "--aggressive" ]; then
            ./docker/cleanup.sh --aggressive
        else
            warning "Это удалит все неиспользуемые Docker ресурсы!"
            read -p "Продолжить? (y/N): " -n 1 -r
            echo
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                ./docker/cleanup.sh
            fi
        fi
    else
        warning "Скрипт очистки не найден, используем стандартную очистку..."
        warning "Это удалит все неиспользуемые Docker ресурсы!"
        read -p "Продолжить? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            log "Очистка Docker..."
            docker system prune -af --volumes
        fi
    fi
}

# ===== DEV-ОКРУЖЕНИЕ =====

dev_up() {
    check_env
    check_docker
    log "Запуск DEV окружения (без пересборки образов)..."
    docker compose -f docker-compose.dev.yml up -d --no-build
    info "✅ DEV окружение запущено:"
    info "🌐 Web: http://localhost:8000"
    info "🗄️ MySQL: localhost:3307"
    info "📚 Redis: localhost:6380"
    info "💡 Для пересборки образов используйте: ./dev.sh dev-build"
}

dev_down() {
    log "Остановка DEV окружения..."
    docker compose -f docker-compose.dev.yml down
}

dev_restart() {
    log "Перезапуск DEV окружения (без пересборки образов)..."
    dev_down
    dev_up
}

dev_build() {
    local no_cache=${1:-}
    if [ "$no_cache" == "--no-cache" ]; then
        log "Полная пересборка DEV образов (без кэша)..."
        docker compose -f docker-compose.dev.yml build --no-cache
    else
        log "Сборка DEV образов (с кэшем)..."
        docker compose -f docker-compose.dev.yml build
    fi
}

dev_logs() {
    local service=${1:-app}
    docker compose -f docker-compose.dev.yml logs -f "$service"
}

dev_shell() {
    docker compose -f docker-compose.dev.yml exec app bash
}

dev_artisan() {
    docker compose -f docker-compose.dev.yml exec app php artisan "$@"
}

dev_composer() {
    docker compose -f docker-compose.dev.yml exec app composer "$@"
}

dev_npm() {
    docker compose -f docker-compose.dev.yml exec app npm "$@"
}

gen_docs_dev() {
    log "Генерация документации (DEV)..."
    docker compose -f docker-compose.dev.yml exec app php artisan scribe:generate
}

dev_lint() {
    log "Форматирование кода (DEV)..."
    docker compose -f docker-compose.dev.yml exec app ./vendor/bin/pint
}

dev_filament_cache() {
    log "Очистка кэша Filament (DEV)..."
    docker compose -f docker-compose.dev.yml exec app php artisan filament:clear-cached-components
}

dev_ide_helper() {
    log "Генерация IDE helper файлов (DEV)..."
    docker compose -f docker-compose.dev.yml exec app php artisan ide-helper:generate
    docker compose -f docker-compose.dev.yml exec app php artisan ide-helper:models --write
    docker compose -f docker-compose.dev.yml exec app php artisan ide-helper:meta
}

dev_migrate() {
    log "Докатывание миграций (DEV, без сброса данных)..."
    docker compose -f docker-compose.dev.yml exec app php artisan migrate --force
}

dev_deploy() {
    log "Обновление проекта (DEV): composer + миграции + кэш..."
    log "1/4 — Установка зависимостей..."
    docker compose -f docker-compose.dev.yml exec app composer install --no-interaction
    log "2/4 — Докатывание миграций..."
    docker compose -f docker-compose.dev.yml exec app php artisan migrate --force
    log "3/4 — Очистка кэшей..."
    docker compose -f docker-compose.dev.yml exec app php artisan config:clear
    docker compose -f docker-compose.dev.yml exec app php artisan cache:clear
    docker compose -f docker-compose.dev.yml exec app php artisan route:clear
    docker compose -f docker-compose.dev.yml exec app php artisan view:clear
    log "4/4 — Оптимизация..."
    docker compose -f docker-compose.dev.yml exec app php artisan config:cache
    docker compose -f docker-compose.dev.yml exec app php artisan route:cache
    info "✅ Проект обновлён!"
}

migrate_fresh_dev() {
    warning "⚠️  ВНИМАНИЕ: Это полностью сбросит базу данных и пересоздаст её с нуля!"
    warning "Если нужно только докатить миграции — используйте: ./dev.sh dev-migrate"
    log "Миграции + сиды (DEV, fresh, force)..."
    docker compose -f docker-compose.dev.yml exec app php artisan migrate:fresh --seed --force
}

test_dev() {
    log "Запуск тестов (DEV)..."
    docker compose -f docker-compose.dev.yml exec app php artisan test
}

# Помощь
help() {
    echo -e "${GREEN}🐳 Laravel Vedma Shop - Docker Management${NC}"
    echo ""
    echo -e "${YELLOW}ПРОДАКШН:${NC}"
    echo "  prod-build      Сборка продакшн образа (с кэшем)"
    echo "  prod-build --no-cache  Полная пересборка без кэша"
    echo "  prod-up         Запуск продакшн (автоочистка старых образов)"
    echo "  prod-deploy     Сборка, запуск, миграции, cache optimize и health"
    echo "  prod-migrate    Докатить миграции (PRODUCTION)"
    echo "  prod-seed       Сиды для fresh install (PRODUCTION, вручную)"
    echo "  prod-optimize   Laravel cache optimize (PRODUCTION)"
    echo "  prod-health     Проверить production health"
    echo "  prod-shell      Консоль в PRODUCTION app"
    echo "  prod-diagnose-upload  Диагностика загрузки картинок (PRODUCTION)"
    echo "  prod-down       Остановка продакшн"
    echo "  prod-logs [srv] Логи продакшн (app, mysql, redis)"
    echo "  prod-backup              Создать бэкап БД + storage/app + .env"
    echo "  prod-restore [name]      Восстановить из бэкапа (latest по умолчанию)"
    echo "  prod-restore --yes       Восстановить latest без подтверждения"
    echo "  prod-backup-list         Список доступных бэкапов"
    echo "  prod-backup-install-cron [час] [мин]  Cron: ежедневный бэкап (03:00)"
    echo ""
    echo -e "${YELLOW}УТИЛИТЫ:${NC}"
    echo "  status          Статус всех контейнеров"
    echo "  logs [service]  Логи Sail сервиса"
    echo "  shell [service] Консоль в контейнер"
    echo "  artisan [cmd]   Выполнить artisan команду"
    echo "  composer [cmd]  Выполнить composer команду"
    echo "  npm [cmd]       Выполнить npm команду"
    echo "  docker-clean    Очистка Docker ресурсов"
    echo "  docker-clean --aggressive  Агрессивная очистка (остановит все контейнеры)"
    echo ""
    echo -e "${YELLOW}DEV-ОКРУЖЕНИЕ:${NC}"
    echo "  dev-up           Запуск DEV (без пересборки образов)"
    echo "  dev-down         Остановка DEV окружения"
    echo "  dev-restart      Перезапуск DEV (без пересборки)"
    echo "  dev-build        Сборка DEV образов (только первый раз или при изменении Dockerfile)"
    echo "  dev-build --no-cache  Полная пересборка без кэша"
    echo "  dev-deploy       Обновить проект: composer + migrate + cache clear"
    echo "  dev-migrate      Докатить миграции (без сброса базы)"
    echo "  dev-freshdb      ⚠️  СБРОС БД + сиды (деструктивно, пересоздаёт базу с нуля)"
    echo "  dev-logs [srv]   Логи DEV (app, mysql_dev, redis_dev)"
    echo "  dev-shell        Консоль в DEV app"
    echo "  dev-artisan [c]  Artisan в DEV"
    echo "  dev-composer [c] Composer в DEV"
    echo "  docs-dev         Генерация документации (DEV)"
    echo "  dev-test         Запуск тестов (DEV)"
    echo "  dev-lint         Форматирование кода Pint (DEV)"
    echo "  dev-filament-cache  Очистка кэша Filament (DEV)"
    echo "  dev-ide-helper   Генерация IDE helper (DEV)"
    echo ""
    echo -e "${YELLOW}ПРИМЕРЫ:${NC}"
    echo "  ./dev.sh dev-build             # Первичная сборка образов (один раз)"
    echo "  ./dev.sh dev-up                # Запуск DEV (образы НЕ пересобираются)"
    echo "  ./dev.sh dev-deploy            # Обновить проект (composer + migrate + cache)"
    echo "  ./dev.sh dev-migrate           # Только докатить новые миграции"
    echo "  ./dev.sh dev-artisan migrate   # Artisan команда в DEV"
    echo "  ./dev.sh dev-shell             # Консоль в DEV контейнер"
    echo "  ./dev.sh prod-build            # Сборка продакшн (с кэшем)"
    echo "  ./dev.sh prod-up               # Запуск продакшн (автоочистка)"
    echo "  ./dev.sh prod-deploy           # Полный production deploy локально/на сервере"
    echo "  ./dev.sh prod-seed             # Сиды для первичной инициализации production"
    echo ""
    echo -e "${BLUE}Для разработки используйте команды без префикса.${NC}"
    echo -e "${BLUE}Для продакшна используйте команды с префиксом 'prod-'.${NC}"
}

# Основная логика
case "${1:-help}" in
    # Sail (разработка)
    up)
        sail_up
        ;;
    down)
        sail_down
        ;;
    restart)
        sail_restart
        ;;
    build)
        sail_build "${2:-}"
        ;;

    # Продакшн
    prod-build)
        prod_build "${2:-}"
        ;;
    prod-up)
        prod_up
        ;;
    prod-deploy)
        prod_deploy "${2:-}"
        ;;
    prod-migrate)
        prod_migrate
        ;;
    prod-seed)
        prod_seed
        ;;
    prod-optimize)
        prod_optimize
        ;;
    prod-health)
        prod_health
        ;;
    prod-shell)
        prod_shell
        ;;
    prod-diagnose-upload)
        prod_diagnose_upload
        ;;
    prod-down)
        prod_down
        ;;
    prod-logs)
        prod_logs "${2:-app}"
        ;;
    prod-backup)
        prod_backup
        ;;
    prod-restore)
        prod_restore "${2:-latest}" "${3:-}"
        ;;
    prod-backup-list)
        prod_backup_list
        ;;
    prod-backup-install-cron)
        prod_backup_install_cron "${2:-3}" "${3:-0}"
        ;;

    # Разработка
    migrate)
        migrate_db
        ;;
    deploy)
        deploy
        ;;
    reset-db)
        reset_db
        ;;
    test)
        run_tests
        ;;
    docs)
        generate_docs
        ;;
    cache)
        clear_cache
        ;;
    lint)
        format_code
        ;;
    ide-helper)
        generate_ide_helpers
        ;;

    # Утилиты
    status)
        status
        ;;
    logs)
        logs "${2:-laravel.test}"
        ;;
    shell)
        shell "${2:-laravel.test}"
        ;;
    artisan)
        shift
        artisan "$@"
        ;;
    composer)
        shift
        composer "$@"
        ;;
    npm)
        shift
        npm "$@"
        ;;
    docker-clean)
        docker_clean "${2:-}"
        ;;

    # DEV-Окружение
    dev-up)
        dev_up
        ;;
    dev-down)
        dev_down
        ;;
    dev-restart)
        dev_restart
        ;;
    dev-build)
        dev_build "${2:-}"
        ;;
    dev-logs)
        dev_logs "${2:-app}"
        ;;
    dev-shell)
        dev_shell
        ;;
    dev-artisan)
        shift
        dev_artisan "$@"
        ;;
    dev-composer)
        shift
        dev_composer "$@"
        ;;
    dev-docs)
        gen_docs_dev
        ;;
    dev-migrate)
        dev_migrate
        ;;
    dev-deploy)
        dev_deploy
        ;;
    dev-freshdb)
        migrate_fresh_dev
        ;;
    dev-test)
        test_dev
        ;;
    dev-lint)
        dev_lint
        ;;
    dev-filament-cache)
        dev_filament_cache
        ;;
    dev-ide-helper)
        dev_ide_helper
        ;;
    help|*)
        help
        ;;
esac


