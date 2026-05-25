# Решение проблемы "no space left on device" в Docker

## Проблема

При деплое возникает ошибка:
```
ERROR: failed to mkdir /var/lib/docker/overlay2/.../merged/app/storage/app/public/7582/conversions: 
mkdir /app/storage/app/public/7582/conversions: no space left on device
```

Это означает, что Docker израсходовал всё доступное место на диске (обычно в `/var/lib/docker/overlay2`).

## Причины

1. **Накопление старых образов** - каждый build создаёт новый образ
2. **Остановленные контейнеры** - занимают место в overlay2
3. **Неиспользуемые volumes** - старые данные БД, Redis и т.д.
4. **Build cache** - кэш сборки образов
5. **Overlay2 файловая система** - может накапливать много слоёв

## Быстрое решение

### 1. Диагностика

Проверьте использование диска:
```bash
# Общее использование диска
df -h

# Использование Docker
docker system df

# Детальная информация о Docker
docker system df -v
```

### 2. Очистка Docker (рекомендуемый способ)

Используйте готовый скрипт:
```bash
chmod +x docker/cleanup.sh
./docker/cleanup.sh
```

Или выполните команды вручную:

```bash
# Удалить остановленные контейнеры
docker container prune -f

# Удалить неиспользуемые образы
docker image prune -a -f

# Удалить неиспользуемые volumes
docker volume prune -f

# Удалить неиспользуемые сети
docker network prune -f

# Очистить build cache
docker builder prune -a -f

# Полная очистка (всё неиспользуемое)
docker system prune -a -f --volumes
```

### 3. Агрессивная очистка (если нужно больше места)

```bash
./docker/cleanup.sh --aggressive
```

Это остановит все контейнеры перед очисткой.

## Долгосрочное решение

### 1. Оптимизация процесса сборки

**Важно**: По умолчанию сборка использует кэш Docker, что значительно экономит место и ускоряет деплой.

```bash
# Обычная сборка (с кэшем) - используется по умолчанию
./dev.sh prod-build

# Полная пересборка (без кэша) - только при необходимости
./dev.sh prod-build --no-cache
```

**Когда использовать `--no-cache`:**
- Изменения в `Dockerfile`
- Обновление системных зависимостей (PHP extensions, системные пакеты)
- Изменения в `composer.json` / `package.json`
- Проблемы с кэшем
- Раз в месяц для профилактики

**Автоматическая очистка старых образов:**
- При запуске `prod-up` автоматически удаляются образы старше 3 дней
- Это предотвращает накопление старых версий

### 2. Автоматическая очистка через cron

Создайте cron-задачу для автоматической очистки:

```bash
# Редактируем crontab
crontab -e

# Добавляем задачу (каждый день в 3:00 ночи)
0 3 * * * /path/to/project/docker/cleanup.sh >> /var/log/docker-cleanup.log 2>&1
```

### 3. Настройка Docker daemon для ограничения размера

Создайте или отредактируйте `/etc/docker/daemon.json`:

```json
{
  "storage-driver": "overlay2",
  "storage-opts": [
    "overlay2.size=20G"
  ],
  "log-driver": "json-file",
  "log-opts": {
    "max-size": "10m",
    "max-file": "3"
  }
}
```

После изменений перезапустите Docker:
```bash
sudo systemctl restart docker
```

**Внимание**: Ограничение размера overlay2 может привести к проблемам, если проект большой. Лучше использовать автоматическую очистку.

### 4. Мониторинг использования диска

Создайте скрипт для мониторинга:

```bash
#!/bin/bash
# monitor-disk.sh

THRESHOLD=80  # Процент использования диска

USAGE=$(df / | tail -1 | awk '{print $5}' | sed 's/%//')

if [ "$USAGE" -gt "$THRESHOLD" ]; then
    echo "⚠️  Использование диска: ${USAGE}%"
    echo "Запускаем очистку Docker..."
    /path/to/project/docker/cleanup.sh
fi
```

Добавьте в cron:
```bash
# Проверка каждые 6 часов
0 */6 * * * /path/to/project/monitor-disk.sh
```

### 5. Оптимизация docker-compose для production

В `docker-compose.production.yml` уже используются named volumes для MySQL и Redis, что правильно. Убедитесь, что:

1. **Volumes монтируются правильно** - не создают лишних копий данных
2. **Build cache очищается** после успешного деплоя
3. **Старые образы удаляются** после обновления

### 6. Настройка CI/CD для очистки

Если используете CI/CD (GitHub Actions, GitLab CI и т.д.), добавьте шаг очистки:

```yaml
# Пример для GitHub Actions
- name: Cleanup Docker
  run: |
    docker system prune -a -f --volumes
    docker builder prune -a -f
```

## Проверка после очистки

```bash
# Проверка использования диска
df -h

# Проверка Docker
docker system df

# Проверка контейнеров
docker ps -a

# Проверка образов
docker images
```

## Профилактика

1. **Регулярная очистка** - настройте автоматическую очистку через cron
2. **Мониторинг** - следите за использованием диска
3. **Оптимизация образов** - используйте multi-stage builds (уже используется)
4. **Удаление старых образов** - после успешного деплоя удаляйте старые версии
5. **Ограничение логов** - настройте ротацию логов Docker

## Полезные команды

```bash
# Показать размер всех образов
docker images --format "table {{.Repository}}\t{{.Tag}}\t{{.Size}}"

# Показать размер всех контейнеров
docker ps -a --format "table {{.Names}}\t{{.Size}}"

# Показать размер всех volumes
docker volume ls -q | xargs docker volume inspect | grep -E "Mountpoint|Name" | paste - - | awk '{print $2, $4}'

# Найти самые большие файлы в Docker
sudo du -h /var/lib/docker | sort -rh | head -20
```

## Если проблема повторяется

1. Проверьте, не накапливаются ли логи приложения
2. Проверьте размер БД (MySQL может занимать много места)
3. Рассмотрите возможность увеличения диска
4. Используйте внешнее хранилище для volumes (NFS, S3 и т.д.)

