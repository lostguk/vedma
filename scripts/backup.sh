#!/bin/bash

# Резервное копирование и восстановление production-окружения (docker-compose.production.yml)

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
COMPOSE_FILE="docker-compose.production.yml"
BACKUP_ROOT="${BACKUP_ROOT:-$PROJECT_ROOT/backups}"
RETENTION_DAYS="${RETENTION_DAYS:-14}"
MYSQL_SERVICE="mysql"
LOG_FILE="${LOG_FILE:-$BACKUP_ROOT/backup.log}"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" >&2
    exit 1
}

info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

read_env_var() {
    local key="$1"
    local value

    value="$(grep -E "^${key}=" "$PROJECT_ROOT/.env" | head -n1 | cut -d= -f2-)"
    value="${value%\"}"
    value="${value#\"}"
    value="${value%\'}"
    value="${value#\'}"

    printf '%s' "$value"
}

load_env() {
    if [ ! -f "$PROJECT_ROOT/.env" ]; then
        error ".env не найден в $PROJECT_ROOT"
    fi

    DB_DATABASE="$(read_env_var DB_DATABASE)"
    DB_PASSWORD="$(read_env_var DB_PASSWORD)"

    if [ -z "$DB_DATABASE" ] || [ -z "$DB_PASSWORD" ]; then
        error "В .env должны быть заданы DB_DATABASE и DB_PASSWORD"
    fi
}

check_docker() {
    if ! command -v docker >/dev/null 2>&1; then
        error "Docker не установлен"
    fi

    if ! docker info >/dev/null 2>&1; then
        error "Docker daemon не запущен"
    fi
}

compose() {
    docker compose -f "$PROJECT_ROOT/$COMPOSE_FILE" "$@"
}

ensure_mysql_running() {
    if ! compose ps --status running --services 2>/dev/null | grep -qx "$MYSQL_SERVICE"; then
        error "Сервис $MYSQL_SERVICE не запущен. Запустите: ./dev.sh prod-up"
    fi
}

resolve_backup_dir() {
    local target="${1:-latest}"

    if [ "$target" = "latest" ]; then
        if [ -L "$BACKUP_ROOT/latest" ]; then
            target="$(readlink "$BACKUP_ROOT/latest")"
        elif [ -d "$BACKUP_ROOT/latest" ]; then
            target="latest"
        else
            error "Бэкап 'latest' не найден. Сначала выполните backup."
        fi
    fi

    local backup_dir="$BACKUP_ROOT/$target"

    if [ ! -d "$backup_dir" ]; then
        error "Бэкап не найден: $backup_dir"
    fi

    if [ ! -f "$backup_dir/database.sql.gz" ]; then
        error "В бэкапе отсутствует database.sql.gz: $backup_dir"
    fi

    printf '%s' "$backup_dir"
}

write_manifest() {
    local backup_dir="$1"
    local db_size files_size

    db_size="$(du -h "$backup_dir/database.sql.gz" | awk '{print $1}')"
    files_size="—"
    if [ -f "$backup_dir/files.tar.gz" ]; then
        files_size="$(du -h "$backup_dir/files.tar.gz" | awk '{print $1}')"
    fi

    cat > "$backup_dir/manifest.txt" <<EOF
timestamp=$(basename "$backup_dir")
created_at=$(date -u +"%Y-%m-%dT%H:%M:%SZ")
database=$DB_DATABASE
database_dump=database.sql.gz (${db_size})
files_archive=files.tar.gz (${files_size})
compose_file=$COMPOSE_FILE
hostname=$(hostname)
project_root=$PROJECT_ROOT
EOF
}

cleanup_old_backups() {
    find "$BACKUP_ROOT" -mindepth 1 -maxdepth 1 -type d -name '20*' -mtime +"$RETENTION_DAYS" -print0 \
        | while IFS= read -r -d '' dir; do
            log "Удаление старого бэкапа: $(basename "$dir")"
            rm -rf "$dir"
        done
}

cmd_backup() {
    load_env
    check_docker
    ensure_mysql_running

    local timestamp backup_dir
    timestamp="$(date +%Y%m%d_%H%M%S)"
    backup_dir="$BACKUP_ROOT/$timestamp"

    mkdir -p "$backup_dir"

    log "Создание бэкапа: $backup_dir"

    log "1/3 — дамп MySQL ($DB_DATABASE)..."
    compose exec -T "$MYSQL_SERVICE" \
        mysqldump -u root -p"$DB_PASSWORD" \
        --single-transaction \
        --routines \
        --triggers \
        --events \
        --set-gtid-purged=OFF \
        "$DB_DATABASE" \
        | gzip > "$backup_dir/database.sql.gz"

    log "2/3 — архив storage/app и .env..."
    tar -czf "$backup_dir/files.tar.gz" \
        -C "$PROJECT_ROOT" \
        storage/app \
        .env

    log "3/3 — manifest и ссылка latest..."
    write_manifest "$backup_dir"
    ln -sfn "$timestamp" "$BACKUP_ROOT/latest"

    cleanup_old_backups

    log "✅ Бэкап готов: $backup_dir"
    info "База: $backup_dir/database.sql.gz"
    info "Файлы: $backup_dir/files.tar.gz"
    info "Latest: $BACKUP_ROOT/latest -> $timestamp"
}

cmd_restore() {
    local target="${1:-latest}"
    local assume_yes="${2:-}"

    load_env
    check_docker
    ensure_mysql_running

    local backup_dir
    backup_dir="$(resolve_backup_dir "$target")"

    warning "⚠️  Восстановление перезапишет текущую БД ($DB_DATABASE) и storage/app!"
    warning "Бэкап: $backup_dir"

    if [ "$assume_yes" != "--yes" ]; then
        read -r -p "Продолжить восстановление? (yes/N): " confirm
        if [ "$confirm" != "yes" ]; then
            info "Отменено."
            exit 0
        fi
    fi

    log "1/3 — восстановление MySQL..."
    gunzip -c "$backup_dir/database.sql.gz" \
        | compose exec -T "$MYSQL_SERVICE" \
            mysql -u root -p"$DB_PASSWORD" "$DB_DATABASE"

    if [ -f "$backup_dir/files.tar.gz" ]; then
        log "2/3 — восстановление storage/app и .env..."
        tar -xzf "$backup_dir/files.tar.gz" -C "$PROJECT_ROOT"
    else
        warning "files.tar.gz не найден, пропускаем восстановление файлов"
    fi

    log "3/3 — очистка кэша приложения..."
    if compose ps --status running --services 2>/dev/null | grep -qx app; then
        compose exec -T app php artisan cache:clear >/dev/null 2>&1 || true
        compose exec -T app php artisan config:clear >/dev/null 2>&1 || true
    fi

    log "✅ Восстановление завершено из: $backup_dir"
}

cmd_list() {
    if [ ! -d "$BACKUP_ROOT" ]; then
        info "Бэкапы ещё не создавались ($BACKUP_ROOT)"
        exit 0
    fi

    info "Доступные бэкапы в $BACKUP_ROOT:"
    echo ""

    local latest_target=""
    if [ -L "$BACKUP_ROOT/latest" ]; then
        latest_target="$(readlink "$BACKUP_ROOT/latest")"
    fi

    find "$BACKUP_ROOT" -mindepth 1 -maxdepth 1 -type d -name '20*' | sort -r | while read -r dir; do
        local name db_size files_size marker=""
        name="$(basename "$dir")"
        db_size="$(du -h "$dir/database.sql.gz" 2>/dev/null | awk '{print $1}' || echo '—')"
        files_size="$(du -h "$dir/files.tar.gz" 2>/dev/null | awk '{print $1}' || echo '—')"

        if [ "$name" = "$latest_target" ]; then
            marker=" ${GREEN}(latest)${NC}"
        fi

        echo -e "  ${BLUE}$name${NC}${marker}"
        echo -e "    DB: ${db_size}, files: ${files_size}"
        if [ -f "$dir/manifest.txt" ]; then
            grep '^created_at=' "$dir/manifest.txt" | sed 's/^created_at=/    created: /'
        fi
        echo ""
    done
}

cmd_install_cron() {
    local cron_hour="${1:-3}"
    local cron_minute="${2:-0}"
    local cron_cmd="cd $PROJECT_ROOT && $SCRIPT_DIR/backup.sh backup >> $LOG_FILE 2>&1"
    local cron_line="$cron_minute $cron_hour * * * $cron_cmd"
    local marker="# vedma-production-backup"

    mkdir -p "$BACKUP_ROOT"

    if crontab -l 2>/dev/null | grep -Fq "$marker"; then
        warning "Cron-задача уже установлена:"
        crontab -l | grep -F "$marker" -A1
        exit 0
    fi

    (
        crontab -l 2>/dev/null || true
        echo "$marker"
        echo "$cron_line"
    ) | crontab -

    log "✅ Cron установлен: ежедневно в $(printf '%02d:%02d' "$cron_hour" "$cron_minute")"
    info "Команда: $cron_cmd"
    info "Лог: $LOG_FILE"
}

cmd_help() {
    cat <<EOF
Использование: ./scripts/backup.sh <команда> [аргументы]

Команды:
  backup                     Создать бэкап БД и файлов
  restore [name|latest]      Восстановить из бэкапа (интерактивное подтверждение)
  restore [name|latest] --yes  Восстановить без подтверждения
  list                       Список доступных бэкапов
  install-cron [hour] [min]  Установить ежедневный cron (по умолчанию 03:00)

Переменные окружения:
  BACKUP_ROOT      Папка бэкапов (по умолчанию: ./backups)
  RETENTION_DAYS   Хранить бэкапы N дней (по умолчанию: 14)
  LOG_FILE         Лог cron-задачи (по умолчанию: ./backups/backup.log)

Примеры:
  ./scripts/backup.sh backup
  ./scripts/backup.sh restore latest
  ./scripts/backup.sh restore 20250613_030000 --yes
  ./scripts/backup.sh install-cron 3 0
EOF
}

main() {
    cd "$PROJECT_ROOT"

    case "${1:-help}" in
        backup)
            cmd_backup
            ;;
        restore)
            cmd_restore "${2:-latest}" "${3:-}"
            ;;
        list)
            cmd_list
            ;;
        install-cron)
            cmd_install_cron "${2:-3}" "${3:-0}"
            ;;
        help|-h|--help)
            cmd_help
            ;;
        *)
            error "Неизвестная команда: ${1:-}. Используйте: ./scripts/backup.sh help"
            ;;
    esac
}

main "$@"
