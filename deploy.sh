#!/bin/bash

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Логирование
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

# Проверка Docker
check_docker() {
    if ! command -v docker &> /dev/null; then
        error "Docker не установлен!"
        exit 1
    fi

    if ! docker info > /dev/null 2>&1; then
        error "Docker не запущен!"
        exit 1
    fi
}

# Проверка .env файла
check_env() {
    if [ ! -f .env.production ]; then
        error ".env.production файл не найден!"
        info "Скопируйте .env.production.example и настройте переменные"
        exit 1
    fi
}

# Сборка образов
build() {
    log "Сборка Docker образов..."
    
    # Экспортируем переменные из .env.production
    export $(grep -v '^#' .env.production | xargs)
    
    docker-compose -f docker-compose.prod.yml build --no-cache
    
    if [ $? -eq 0 ]; then
        log "Сборка завершена успешно!"
    else
        error "Ошибка при сборке образов!"
        exit 1
    fi
}

# Запуск в продакшне
deploy() {
    check_docker
    check_env
    
    log "Деплой приложения в продакшн..."
    
    # Экспортируем переменные
    export $(grep -v '^#' .env.production | xargs)
    
    # Останавливаем старые контейнеры
    docker-compose -f docker-compose.prod.yml down
    
    # Запускаем новые
    docker-compose -f docker-compose.prod.yml up -d
    
    # Ждем запуска
    sleep 30
    
    # Проверяем статус
    if docker-compose -f docker-compose.prod.yml ps | grep -q "Up"; then
        log "Приложение успешно запущено!"
        info "Проверьте статус: ./deploy.sh status"
    else
        error "Ошибка при запуске приложения!"
        docker-compose -f docker-compose.prod.yml logs
        exit 1
    fi
}

# Статус контейнеров
status() {
    log "Статус контейнеров:"
    docker-compose -f docker-compose.prod.yml ps
}

# Логи
logs() {
    local service=${1:-}
    if [ -n "$service" ]; then
        docker-compose -f docker-compose.prod.yml logs -f "$service"
    else
        docker-compose -f docker-compose.prod.yml logs -f
    fi
}

# Остановка
stop() {
    log "Остановка приложения..."
    docker-compose -f docker-compose.prod.yml down
}

# Резервное копирование
backup() {
    log "Создание резервной копии..."
    
    # Создаем директорию для бэкапов
    mkdir -p backups
    
    # Создаем timestamp
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    
    # Бэкап базы данных
    docker-compose -f docker-compose.prod.yml exec mysql mysqldump \
        -u${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE} \
        > backups/db_backup_${TIMESTAMP}.sql
    
    # Бэкап файлов storage
    docker run --rm \
        -v vedma_app_storage:/data \
        -v $(pwd)/backups:/backup \
        alpine tar czf /backup/storage_backup_${TIMESTAMP}.tar.gz -C /data .
    
    log "Резервная копия создана: backups/backup_${TIMESTAMP}"
}

# Восстановление
restore() {
    local backup_file=${1:-}
    
    if [ -z "$backup_file" ]; then
        error "Укажите файл для восстановления!"
        exit 1
    fi
    
    warning "Восстановление перезапишет текущие данные!"
    read -p "Продолжить? (y/N): " -n 1 -r
    echo
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        log "Восстановление из $backup_file..."
        # Логика восстановления
    fi
}

# Мониторинг
monitor() {
    log "Мониторинг системы:"
    
    # Использование CPU и памяти
    docker stats --no-stream
    
    # Место на диске
    echo ""
    info "Использование диска:"
    df -h
    
    # Проверка health checks
    echo ""
    info "Health checks:"
    docker-compose -f docker-compose.prod.yml ps
}

# Обновление
update() {
    log "Обновление приложения..."
    
    # Создаем бэкап перед обновлением
    backup
    
    # Сборка новых образов
    build
    
    # Деплой
    deploy
}

# Помощь
help() {
    echo -e "${GREEN}Скрипт управления продакшн окружением${NC}"
    echo ""
    echo -e "${YELLOW}Использование:${NC}"
    echo "  ./deploy.sh [команда]"
    echo ""
    echo -e "${YELLOW}Команды:${NC}"
    echo "  build      Сборка Docker образов"
    echo "  deploy     Деплой в продакшн"
    echo "  status     Статус контейнеров"
    echo "  logs       Просмотр логов"
    echo "  stop       Остановка приложения"
    echo "  backup     Создание резервной копии"
    echo "  restore    Восстановление из резервной копии"
    echo "  monitor    Мониторинг системы"
    echo "  update     Обновление приложения"
    echo "  help       Показать эту справку"
    echo ""
    echo -e "${YELLOW}Примеры:${NC}"
    echo "  ./deploy.sh deploy"
    echo "  ./deploy.sh logs app"
    echo "  ./deploy.sh backup"
    echo "  ./deploy.sh restore backups/backup_20240101_120000"
}

# Основная логика
case "${1:-help}" in
    build)
        build
        ;;
    deploy)
        deploy
        ;;
    status)
        status
        ;;
    logs)
        logs "${2:-}"
        ;;
    stop)
        stop
        ;;
    backup)
        backup
        ;;
    restore)
        restore "${2:-}"
        ;;
    monitor)
        monitor
        ;;
    update)
        update
        ;;
    help|*)
        help
        ;;
esac