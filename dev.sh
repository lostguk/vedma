#!/bin/bash

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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
    log "Остановка и удаление контейнеров..."
    docker-compose -f docker-compose.dev.yml down --remove-orphans
}

# Запуск в development режиме
up() {
    check_env
    export_env
    check_docker
    
    log "Запуск контейнеров в development режиме..."
    docker-compose -f docker-compose.dev.yml up -d --build
    
    log "Ожидание запуска контейнеров..."
    sleep 10
    
    info "Контейнеры запущены:"
    info "- Приложение: http://localhost:8000"
    info "- Adminer: http://localhost:8081"
    info "- MySQL: localhost:3307"
    info "- Redis: localhost:6378"
}

# Полная пересборка
rebuild() {
    log "Полная пересборка контейнеров..."
    down
    docker-compose -f docker-compose.dev.yml build --no-cache
    up
}

# Сброс базы данных и сиды
reset_db() {
    log "Сброс базы данных..."
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan migrate:fresh --seed --force
}

# Запуск тестов
test() {
    log "Запуск тестов..."
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan test
}

# Генерация документации
docs() {
    log "Генерация API документации..."
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan scribe:generate
}

# Очистка кэша Filament
filament_cache() {
    log "Очистка кэша Filament..."
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan filament:clear-cached-components
}

# Форматирование кода
lint() {
    log "Форматирование кода с помощью Pint..."
    docker-compose -f docker-compose.dev.yml exec php gosu appuser ./vendor/bin/pint
}

# Генерация IDE helper
ide_helper() {
    log "Генерация IDE helper файлов..."
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan ide-helper:generate
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan ide-helper:models --write
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan ide-helper:meta
}

# Логи контейнеров
logs() {
    local service=${1:-}
    if [ -n "$service" ]; then
        docker-compose -f docker-compose.dev.yml logs -f "$service"
    else
        docker-compose -f docker-compose.dev.yml logs -f
    fi
}

# Консоль контейнера
shell() {
    local service=${1:-php}
    log "Подключение к контейнеру $service..."
    docker-compose -f docker-compose.dev.yml exec "$service" /bin/bash
}

# Artisan команды
artisan() {
    docker-compose -f docker-compose.dev.yml exec php gosu appuser php artisan "$@"
}

# Composer команды
composer() {
    docker-compose -f docker-compose.dev.yml exec php gosu appuser composer "$@"
}

# Состояние контейнеров
status() {
    log "Состояние контейнеров:"
    docker-compose -f docker-compose.dev.yml ps
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
    log "Проверка безопасности контейнеров..."
    
    # Проверка, что контейнеры не запущены как root
    for container in shop_nginx_dev shop_php_dev; do
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
    docker-compose -f docker-compose.dev.yml config --services | while read service; do
        info "Сервис: $service"
    done
}

# Помощь
help() {
    echo -e "${GREEN}Скрипт управления Docker контейнерами для разработки${NC}"
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
    echo "  help            Показать эту справку"
    echo ""
    echo -e "${YELLOW}Примеры:${NC}"
    echo "  ./dev.sh up"
    echo "  ./dev.sh logs nginx"
    echo "  ./dev.sh shell php"
    echo "  ./dev.sh artisan migrate"
    echo "  ./dev.sh composer install"
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
    help|*)
        help
        ;;
esac
