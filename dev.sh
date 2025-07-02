#!/bin/bash

# Улучшенный скрипт управления Docker окружением для Laravel
# Версия: 2.0 (Security Enhanced)

set -euo pipefail

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Функция для вывода с цветом
print_colored() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${NC}"
}

# Функция для проверки Docker
check_docker() {
    if ! command -v docker &> /dev/null; then
        print_colored $RED "❌ Docker не найден. Пожалуйста, установите Docker."
        exit 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        print_colored $RED "❌ Docker Compose не найден. Пожалуйста, установите Docker Compose."
        exit 1
    fi
}

# Функция для проверки .env файла
check_env() {
    if [ ! -f .env ]; then
        print_colored $YELLOW "⚠️  .env файл не найден. Копирую из .env.example..."
        if [ -f .env.example ]; then
            cp .env.example .env
            print_colored $GREEN "✅ .env файл создан. Пожалуйста, настройте переменные окружения."
        else
            print_colored $RED "❌ .env.example файл не найден."
            exit 1
        fi
    fi
    
    # Проверяем права на .env файл
    ENV_PERMS=$(stat -c "%a" .env)
    if [[ "$ENV_PERMS" != "600" && "$ENV_PERMS" != "644" ]]; then
        print_colored $YELLOW "⚠️  Устанавливаю безопасные права на .env файл..."
        chmod 600 .env
    fi
}

# Функция для создания секретов
create_secrets() {
    if [ ! -d "secrets" ]; then
        mkdir -p secrets
        print_colored $YELLOW "📁 Создана директория secrets"
    fi
    
    if [ ! -f "secrets/mysql_root_password.txt" ]; then
        print_colored $YELLOW "🔐 Создаю файл с паролем MySQL root. ИЗМЕНИТЕ ЕГО НА ПРОДАКШЕНЕ!"
        echo "CHANGE_THIS_SECURE_PASSWORD_123!" > secrets/mysql_root_password.txt
        chmod 600 secrets/mysql_root_password.txt
    fi
    
    if [ ! -f "secrets/mysql_password.txt" ]; then
        print_colored $YELLOW "🔐 Создаю файл с паролем MySQL user. ИЗМЕНИТЕ ЕГО НА ПРОДАКШЕНЕ!"
        echo "CHANGE_THIS_USER_PASSWORD_456!" > secrets/mysql_password.txt
        chmod 600 secrets/mysql_password.txt
    fi
}

# Функция для установки переменных окружения для dev
setup_dev_env() {
    export HOST_UID=$(id -u)
    export HOST_GID=$(id -g)
    print_colored $BLUE "🔧 HOST_UID=$HOST_UID, HOST_GID=$HOST_GID"
}

# Функция для запуска dev окружения
dev_up() {
    print_colored $BLUE "🚀 Запуск development окружения..."
    setup_dev_env
    check_env
    
    # Включаем dev конфигурацию PHP
    if [ -f "docker/php/php-dev.ini.disabled" ]; then
        cp docker/php/php-dev.ini.disabled docker/php/php-dev.ini
    fi
    
    docker-compose -f docker-compose.dev.yml up -d --build
    print_colored $GREEN "✅ Development окружение запущено!"
    print_colored $BLUE "🌐 Приложение доступно по адресу: http://localhost:8000"
    print_colored $BLUE "🗄️  Adminer доступен по адресу: http://localhost:8081"
}

# Функция для запуска production окружения
prod_up() {
    print_colored $BLUE "🚀 Запуск production окружения..."
    check_env
    create_secrets
    
    # Отключаем dev конфигурацию PHP
    if [ -f "docker/php/php-dev.ini" ]; then
        mv docker/php/php-dev.ini docker/php/php-dev.ini.disabled
    fi
    
    docker-compose -f docker-compose.yml up -d --build
    print_colored $GREEN "✅ Production окружение запущено!"
}

# Функция для остановки
down() {
    print_colored $YELLOW "⏹️  Остановка контейнеров..."
    docker-compose -f docker-compose.dev.yml down 2>/dev/null || true
    docker-compose -f docker-compose.yml down 2>/dev/null || true
    print_colored $GREEN "✅ Контейнеры остановлены!"
}

# Функция для полного сброса базы данных
reset_db() {
    print_colored $YELLOW "🗄️  Сброс базы данных..."
    docker-compose -f docker-compose.dev.yml exec php php artisan migrate:fresh --seed --force
    print_colored $GREEN "✅ База данных сброшена и заполнена!"
}

# Функция для запуска тестов с безопасностью
test() {
    print_colored $BLUE "🧪 Запуск тестов..."
    docker-compose -f docker-compose.dev.yml exec php php artisan test --parallel
    print_colored $GREEN "✅ Тесты завершены!"
}

# Функция для генерации документации
docs() {
    print_colored $BLUE "📚 Генерация API документации..."
    docker-compose -f docker-compose.dev.yml exec php php artisan scribe:generate
    print_colored $GREEN "✅ Документация сгенерирована!"
}

# Функция для очистки кэша Filament
filament_cache() {
    print_colored $BLUE "🧹 Очистка кэша Filament..."
    docker-compose -f docker-compose.dev.yml exec php php artisan filament:clear-cached-components
    docker-compose -f docker-compose.dev.yml exec php php artisan view:clear
    print_colored $GREEN "✅ Кэш Filament очищен!"
}

# Функция для форматирования кода
lint() {
    print_colored $BLUE "✨ Форматирование кода с помощью Pint..."
    docker-compose -f docker-compose.dev.yml exec php ./vendor/bin/pint
    print_colored $GREEN "✅ Код отформатирован!"
}

# Функция для генерации IDE helper
ide_helper() {
    print_colored $BLUE "🔧 Генерация IDE helper файлов..."
    docker-compose -f docker-compose.dev.yml exec php php artisan ide-helper:generate
    docker-compose -f docker-compose.dev.yml exec php php artisan ide-helper:models --nowrite
    docker-compose -f docker-compose.dev.yml exec php php artisan ide-helper:meta
    print_colored $GREEN "✅ IDE helper файлы сгенерированы!"
}

# Функция для проверки безопасности
security_scan() {
    print_colored $BLUE "🔍 Проверка безопасности..."
    
    # Проверка Composer зависимостей
    docker-compose -f docker-compose.dev.yml exec php composer audit
    
    # Проверка прав на файлы
    print_colored $BLUE "📁 Проверка прав доступа к файлам..."
    find . -name "*.env*" -not -path "./node_modules/*" -exec ls -la {} \;
    find ./secrets -type f -exec ls -la {} \; 2>/dev/null || true
    
    print_colored $GREEN "✅ Проверка безопасности завершена!"
}

# Функция для мониторинга логов
logs() {
    local service=${2:-}
    if [ -n "$service" ]; then
        print_colored $BLUE "📋 Просмотр логов сервиса: $service"
        docker-compose -f docker-compose.dev.yml logs -f "$service"
    else
        print_colored $BLUE "📋 Просмотр всех логов"
        docker-compose -f docker-compose.dev.yml logs -f
    fi
}

# Функция для бэкапа базы данных
backup_db() {
    print_colored $BLUE "💾 Создание бэкапа базы данных..."
    local timestamp=$(date +%Y%m%d_%H%M%S)
    local backup_file="backup_${timestamp}.sql"
    
    docker-compose -f docker-compose.dev.yml exec mysql mysqldump -u root -p"$DB_PASSWORD" "$DB_DATABASE" > "$backup_file"
    print_colored $GREEN "✅ Бэкап создан: $backup_file"
}

# Функция для показа статуса
status() {
    print_colored $BLUE "📊 Статус контейнеров:"
    docker-compose -f docker-compose.dev.yml ps
    
    print_colored $BLUE "💾 Использование дискового пространства:"
    docker system df
    
    print_colored $BLUE "🔍 Проверка безопасности контейнеров:"
    docker-compose -f docker-compose.dev.yml ps --format "table {{.Name}}\t{{.Status}}\t{{.Ports}}" | grep -E "(shop_|Name)"
}

# Функция для помощи
help() {
    print_colored $BLUE "🚀 Laravel Docker Management Script v2.0 (Security Enhanced)"
    echo ""
    echo "Использование: $0 [команда]"
    echo ""
    echo "Команды:"
    print_colored $GREEN "  dev-up          - Запуск development окружения"
    print_colored $GREEN "  prod-up         - Запуск production окружения"
    print_colored $GREEN "  down            - Остановка всех контейнеров"
    print_colored $GREEN "  reset-db        - Полный сброс базы данных с сидами"
    print_colored $GREEN "  test            - Запуск тестов"
    print_colored $GREEN "  docs            - Генерация API документации"
    print_colored $GREEN "  filament-cache  - Очистка кэша Filament"
    print_colored $GREEN "  lint            - Форматирование кода (Pint)"
    print_colored $GREEN "  ide-helper      - Генерация IDE helper файлов"
    print_colored $GREEN "  security-scan   - Проверка безопасности"
    print_colored $GREEN "  logs [service]  - Просмотр логов (опционально для конкретного сервиса)"
    print_colored $GREEN "  backup-db       - Создание бэкапа базы данных"
    print_colored $GREEN "  status          - Показать статус системы"
    print_colored $GREEN "  help            - Показать эту справку"
    echo ""
    print_colored $YELLOW "⚠️  Перед использованием в продакшене обязательно:"
    print_colored $YELLOW "   1. Измените пароли в ./secrets/"
    print_colored $YELLOW "   2. Настройте переменные окружения в .env"
    print_colored $YELLOW "   3. Настройте SSL сертификаты"
    print_colored $YELLOW "   4. Выполните security-scan"
}

# Основная логика
main() {
    check_docker
    
    case "${1:-help}" in
        "dev-up")
            dev_up
            ;;
        "prod-up")
            prod_up
            ;;
        "down")
            down
            ;;
        "reset-db")
            reset_db
            ;;
        "test")
            test
            ;;
        "docs")
            docs
            ;;
        "filament-cache")
            filament_cache
            ;;
        "lint")
            lint
            ;;
        "ide-helper")
            ide_helper
            ;;
        "security-scan")
            security_scan
            ;;
        "logs")
            logs "$@"
            ;;
        "backup-db")
            backup_db
            ;;
        "status")
            status
            ;;
        "help"|*)
            help
            ;;
    esac
}

main "$@"
