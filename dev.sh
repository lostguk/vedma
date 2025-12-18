#!/bin/bash

# Laravel Sail + Production Docker Management Script
# Простой и понятный скрипт для управления окружениями

set -e

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
    log "Пересборка Laravel Sail..."
    ./vendor/bin/sail build --no-cache
}

# ===== ПРОДАКШН =====

prod_build() {
    check_env
    check_docker

    log "Сборка продакшн образа..."
    docker compose -f docker-compose.production.yml build --no-cache
}

prod_up() {
    check_env
    check_docker

    log "Запуск продакшн окружения..."
            docker compose -f docker-compose.production.yml up -d

    info "✅ Продакшн запущен:"
    info "🌐 Web: http://localhost:8080"
    info "🗄️ MySQL: localhost:3306"
    info "📚 Redis: localhost:6379"
}

prod_down() {
    log "Остановка продакшн окружения..."
            docker compose -f docker-compose.production.yml down
}

prod_logs() {
    local service=${1:-app}
            docker compose -f docker-compose.production.yml logs -f "$service"
}

# ===== ОБЩИЕ КОМАНДЫ =====

reset_db() {
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
    log "Запуск DEV окружения..."
    docker compose -f docker-compose.dev.yml up -d
    info "✅ DEV окружение запущено:"
    info "🌐 Web: http://localhost:8000"
    info "🗄️ MySQL: localhost:3307"
    info "📚 Redis: localhost:6380"
}

dev_down() {
    log "Остановка DEV окружения..."
    docker compose -f docker-compose.dev.yml down
}

dev_restart() {
    log "Перезапуск DEV окружения..."
    dev_down
    dev_up
}

dev_build() {
    log "Пересборка DEV образов..."
    docker compose -f docker-compose.dev.yml build --no-cache
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

migrate_fresh_dev() {
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
    echo "  prod-build      Сборка продакшн образа"
    echo "  prod-up         Запуск продакшн"
    echo "  prod-down       Остановка продакшн"
    echo "  prod-logs [srv] Логи продакшн (app, mysql, redis)"
    echo ""
    echo -e "${YELLOW}РАЗРАБОТКА:${NC}"
    echo "  reset-db        Сброс БД + сиды"
    echo "  test            Запуск тестов"
    echo "  docs            Генерация API документации"
    echo "  cache           Очистка кэшей"
    echo "  lint            Форматирование кода"
    echo "  ide-helper      Генерация IDE helper"
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
    echo "  dev-up           Запуск DEV окружения"
    echo "  dev-down         Остановка DEV окружения"
    echo "  dev-restart      Перезапуск DEV окружения"
    echo "  dev-build        Пересборка DEV образов"
    echo "  dev-logs [srv]   Логи DEV (app, mysql_dev, redis_dev)"
    echo "  dev-shell        Консоль в DEV app"
    echo "  dev-artisan [c]  Artisan в DEV"
    echo "  dev-composer [c] Composer в DEV"
    echo "  docs-dev     Генерация документации (DEV)"
    echo "  dev-freshdb  Миграции + сиды (fresh, force, DEV)"
    echo "  dev-test         Запуск тестов (DEV)"
    echo ""
    echo -e "${YELLOW}ПРИМЕРЫ:${NC}"
    echo "  ./dev.sh up                    # Запуск разработки"
    echo "  ./dev.sh artisan migrate       # Миграции"
    echo "  ./dev.sh shell                 # Консоль PHP контейнера"
    echo "  ./dev.sh prod-up              # Запуск продакшн"
    echo "  ./dev.sh prod-logs nginx      # Логи nginx в продакшн"
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
        sail_build
        ;;

    # Продакшн
    prod-build)
        prod_build
        ;;
    prod-up)
        prod_up
        ;;
    prod-down)
        prod_down
        ;;
    prod-logs)
        prod_logs "${2:-app}"
        ;;

    # Разработка
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
        dev_build
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
    dev-freshdb)
        migrate_fresh_dev
        ;;
    dev-test)
        test_dev
        ;;
    help|*)
        help
        ;;
esac


