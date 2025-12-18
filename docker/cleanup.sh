#!/bin/bash

# Скрипт для очистки Docker от неиспользуемых ресурсов
# Использование: ./docker/cleanup.sh [--aggressive]

set -e

AGGRESSIVE=false

if [[ "$1" == "--aggressive" ]]; then
    AGGRESSIVE=true
fi

echo "🧹 Начинаем очистку Docker..."

# Показываем текущее использование диска
echo ""
echo "📊 Текущее использование диска:"
df -h / | tail -1

echo ""
echo "📦 Использование Docker:"
docker system df

# Остановка всех контейнеров (опционально, только если нужно)
if [[ "$AGGRESSIVE" == "true" ]]; then
    echo ""
    echo "⏹️  Останавливаем все контейнеры..."
    docker stop $(docker ps -aq) 2>/dev/null || true
fi

# Удаление остановленных контейнеров
echo ""
echo "🗑️  Удаляем остановленные контейнеры..."
docker container prune -f

# Удаление неиспользуемых образов
echo ""
echo "🖼️  Удаляем неиспользуемые образы..."
docker image prune -a -f

# Удаление неиспользуемых volumes
echo ""
echo "💾 Удаляем неиспользуемые volumes..."
docker volume prune -f

# Удаление неиспользуемых сетей
echo ""
echo "🌐 Удаляем неиспользуемые сети..."
docker network prune -f

# Очистка build cache
echo ""
echo "🔨 Очищаем build cache..."
docker builder prune -a -f

# Полная очистка системы (все неиспользуемое)
echo ""
echo "🧼 Полная очистка системы..."
docker system prune -a -f --volumes

# Показываем результат
echo ""
echo "✅ Очистка завершена!"
echo ""
echo "📊 Использование диска после очистки:"
df -h / | tail -1

echo ""
echo "📦 Использование Docker после очистки:"
docker system df

