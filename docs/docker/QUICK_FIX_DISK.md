# Быстрое решение проблемы "no space left on device"

## 🚨 Срочное решение (выполнить на сервере)

### Шаг 1: Проверка использования диска

```bash
df -h
docker system df
```

### Шаг 2: Очистка Docker

Если у вас есть доступ к проекту:

```bash
cd /path/to/project
chmod +x docker/cleanup.sh
./docker/cleanup.sh
```

Или вручную:

```bash
# Удалить остановленные контейнеры
docker container prune -f

# Удалить неиспользуемые образы
docker image prune -a -f

# Удалить неиспользуемые volumes (ОСТОРОЖНО: может удалить данные БД!)
docker volume prune -f

# Очистить build cache
docker builder prune -a -f

# Полная очистка
docker system prune -a -f --volumes
```

### Шаг 3: Проверка результата

```bash
df -h
docker system df
```

### Шаг 4: Повторный деплой

После очистки попробуйте снова задеплоить проект.

## ⚠️ Важно

- **Не удаляйте volumes, если не уверены** - они могут содержать данные БД
- **Сначала удалите образы и контейнеры**, затем volumes при необходимости
- **Сохраните важные данные** перед агрессивной очисткой

## 🔄 Автоматизация (после решения проблемы)

Добавьте в cron для автоматической очистки:

```bash
# Редактируем crontab
crontab -e

# Добавляем (каждый день в 3:00)
0 3 * * * /path/to/project/docker/cleanup.sh >> /var/log/docker-cleanup.log 2>&1
```

## 📚 Подробная документация

См. [DISK_CLEANUP.md](./DISK_CLEANUP.md) для полного решения проблемы.

