#!/bin/bash

# Разработческий скрипт для управления Docker-окружением
# Использование: ./dev.sh [команда]

set -e

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Определение операционной системы
detect_os() {
    if [[ "$OSTYPE" == "darwin"* ]]; then
        echo "mac"
    else
        echo "linux"
    fi
}

# Выбор Docker Compose файла в зависимости от ОС
get_compose_file() {
    local os=$(detect_os)
    if [ "$os" = "mac" ]; then
        echo "docker-compose.local.yml"
    else
        echo "docker-compose.dev.yml"
    fi
}

# Функция для логирования
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" >&2
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
        error ".env файл не найден!"
        info "Скопируйте .env.example в .env и настройте переменные"
        exit 1
    fi
}

# Экспорт переменных из .env
export_env() {
    if [ -f .env ]; then
        export $(grep -v '^#' .env | xargs)
        export HOST_UID=$(id -u)
        export HOST_GID=$(id -g)
    fi
}

# Функция для проверки Docker
check_docker() {
    if ! command -v docker &> /dev/null; then
        error "Docker не установлен!"
        exit 1
    fi

    if ! command -v docker-compose &> /dev/null; then
        error "Docker Compose не установлен!"
        exit 1
    fi
}

# Остановка и удаление контейнеров
down() {
    local compose_file=$(get_compose_file)
    log "Остановка и удаление контейнеров ($compose_file)..."
    docker-compose -f "$compose_file" down --remove-orphans
}

# Запуск в development режиме
up() {
    check_env
    export_env
    check_docker

    local compose_file=$(get_compose_file)
    local os=$(detect_os)

    log "Запуск контейнеров ($compose_file) для $os..."

    if [ "$os" = "mac" ]; then
        info "Настройка для macOS:"
        info "- Используется кэширование файловой системы"
        info "- Отдельные volumes для vendor и node_modules"
        info "- Оптимизированы настройки производительности"
    fi

    docker-compose -f "$compose_file" up -d --build

    log "Ожидание запуска контейнеров..."
    sleep 10

    info "Контейнеры запущены:"
    info "- Приложение: http://localhost:8000"
    info "- Adminer: http://localhost:8081"
    info "- MySQL: localhost:3307"
    info "- Redis: localhost:6378"

    if [ "$os" = "mac" ]; then
        info "Mac специфичные особенности:"
        info "- Vendor и node_modules используют Docker volumes для производительности"
        info "- Может потребоваться время для синхронизации файлов"
    fi
}

# Полная пересборка
rebuild() {
    local compose_file=$(get_compose_file)
    log "Полная пересборка контейнеров ($compose_file)..."
    down
    docker-compose -f "$compose_file" build --no-cache
    up
}

# Сброс базы данных и сиды
reset_db() {
    local compose_file=$(get_compose_file)
    log "Сброс базы данных..."
    docker-compose -f "$compose_file" exec php gosu appuser php artisan migrate:fresh --seed --force
}

# Запуск тестов
test() {
    local compose_file=$(get_compose_file)
    log "Запуск тестов..."
    docker-compose -f "$compose_file" exec php gosu appuser php artisan test
}

# Генерация документации
docs() {
    local compose_file=$(get_compose_file)
    log "Генерация API документации..."
    docker-compose -f "$compose_file" exec php gosu appuser php artisan scribe:generate
}

# Очистка кэша Filament
filament_cache() {
    local compose_file=$(get_compose_file)
    log "Очистка кэша Filament..."
    docker-compose -f "$compose_file" exec php gosu appuser php artisan filament:clear-cached-components
}

# Форматирование кода
lint() {
    local compose_file=$(get_compose_file)
    log "Форматирование кода с помощью Pint..."
    docker-compose -f "$compose_file" exec php gosu appuser ./vendor/bin/pint
}

# Генерация IDE helper
ide_helper() {
    local compose_file=$(get_compose_file)
    log "Генерация IDE helper файлов..."
    docker-compose -f "$compose_file" exec php gosu appuser php artisan ide-helper:generate
    docker-compose -f "$compose_file" exec php gosu appuser php artisan ide-helper:models --write
    docker-compose -f "$compose_file" exec php gosu appuser php artisan ide-helper:meta
}

# Логи контейнеров
logs() {
    local compose_file=$(get_compose_file)
    local service=${1:-}
    if [ -n "$service" ]; then
        docker-compose -f "$compose_file" logs -f "$service"
    else
        docker-compose -f "$compose_file" logs -f
    fi
}

# Консоль контейнера
shell() {
    local compose_file=$(get_compose_file)
    local service=${1:-php}
    log "Подключение к контейнеру $service..."
    docker-compose -f "$compose_file" exec "$service" /bin/bash
}

# Artisan команды
artisan() {
    local compose_file=$(get_compose_file)
    docker-compose -f "$compose_file" exec php gosu appuser php artisan "$@"
}

# Composer команды
composer() {
    local compose_file=$(get_compose_file)
    docker-compose -f "$compose_file" exec php gosu appuser composer "$@"
}

# Состояние контейнеров
status() {
    local compose_file=$(get_compose_file)
    log "Состояние контейнеров ($compose_file):"
    docker-compose -f "$compose_file" ps
}

# Очистка Docker
clean() {
    warning "Это удалит все неиспользуемые Docker ресурсы!"
    read -p "Продолжить? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        log "Очистка Docker ресурсов..."
        docker system prune -af --volumes
    fi
}

# Проверка безопасности
security_check() {
    local compose_file=$(get_compose_file)
    local os=$(detect_os)

    log "Проверка безопасности контейнеров ($os)..."

    # Определяем суффикс контейнеров в зависимости от ОС
    local suffix
    if [ "$os" = "mac" ]; then
        suffix="mac"
    else
        suffix="dev"
    fi

    # Проверка, что контейнеры не запущены как root
    for container in "shop_nginx_$suffix" "shop_php_$suffix"; do
        if docker ps --format "table {{.Names}}" | grep -q "$container"; then
            user=$(docker exec "$container" whoami 2>/dev/null || echo "unknown")
            if [ "$user" = "root" ]; then
                warning "Контейнер $container запущен от имени root!"
            else
                info "Контейнер $container: пользователь $user ✓"
            fi
        fi
    done

    # Проверка capabilities
    log "Проверка capabilities контейнеров..."
    docker-compose -f "$compose_file" config --services | while read service; do
        info "Сервис: $service"
    done
}

# Синхронизация vendor (для Mac)
sync_vendor() {
    local os=$(detect_os)
    if [ "$os" != "mac" ]; then
        warning "Команда sync-vendor доступна только на Mac"
        return 1
    fi

    local compose_file=$(get_compose_file)
    log "Синхронизация vendor для Mac..."

    # Устанавливаем зависимости в volume
    docker-compose -f "$compose_file" exec php gosu appuser composer install --optimize-autoloader

    log "Vendor синхронизирован"
}

# Помощь
help() {
    local os=$(detect_os)
    echo -e "${GREEN}Скрипт управления Docker контейнерами для разработки${NC}"
    echo -e "${BLUE}Текущая ОС: $os${NC}"
    echo ""
    echo -e "${YELLOW}Использование:${NC}"
    echo "  ./dev.sh [команда] [параметры]"
    echo ""
    echo -e "${YELLOW}Команды:${NC}"
    echo "  up              Запуск контейнеров"
    echo "  down            Остановка контейнеров"
    echo "  rebuild         Полная пересборка"
    echo "  reset-db        Сброс БД и загрузка сидов"
    echo "  test            Запуск тестов"
    echo "  docs            Генерация документации"
    echo "  filament-cache  Очистка кэша Filament"
    echo "  lint            Форматирование кода"
    echo "  ide-helper      Генерация IDE helper"
    echo "  logs [service]  Просмотр логов"
    echo "  shell [service] Консоль контейнера (по умолчанию php)"
    echo "  artisan [cmd]   Выполнение Artisan команд"
    echo "  composer [cmd]  Выполнение Composer команд"
    echo "  status          Состояние контейнеров"
    echo "  clean           Очистка Docker ресурсов"
    echo "  security-check  Проверка безопасности"
    if [ "$os" = "mac" ]; then
        echo "  sync-vendor     Синхронизация vendor (Mac)"
    fi
    echo "  help            Показать эту справку"
    echo ""
    echo -e "${YELLOW}Примеры:${NC}"
    echo "  ./dev.sh up"
    echo "  ./dev.sh logs nginx"
    echo "  ./dev.sh shell php"
    echo "  ./dev.sh artisan migrate"
    echo "  ./dev.sh composer install"

    if [ "$os" = "mac" ]; then
        echo ""
        echo -e "${YELLOW}Mac особенности:${NC}"
        echo "  - Автоматически используется docker-compose.local.yml"
        echo "  - Vendor и node_modules хранятся в Docker volumes"
        echo "  - Файловая система кэшируется для производительности"
        echo "  - Используйте sync-vendor для обновления зависимостей"
    fi
}

# Основная логика
case "${1:-help}" in
    up)
        up
        ;;
    down)
        down
        ;;
    rebuild)
        rebuild
        ;;
    reset-db)
        reset_db
        ;;
    test)
        test
        ;;
    docs)
        docs
        ;;
    filament-cache)
        filament_cache
        ;;
    lint)
        lint
        ;;
    ide-helper)
        ide_helper
        ;;
    logs)
        logs "${2:-}"
        ;;
    shell)
        shell "${2:-php}"
        ;;
    artisan)
        shift
        artisan "$@"
        ;;
    composer)
        shift
        composer "$@"
        ;;
    status)
        status
        ;;
    clean)
        clean
        ;;
    security-check)
        security_check
        ;;
    sync-vendor)
        sync_vendor
        ;;
    help|*)
        help
        ;;
esac
