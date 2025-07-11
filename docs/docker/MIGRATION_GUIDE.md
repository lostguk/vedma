# Руководство по миграции на новую Docker конфигурацию

## Обзор изменений

Проект переведен на новую Docker архитектуру с двумя окружениями:
1. **Laravel Sail** - для локальной разработки
2. **Production Docker** - оптимизированное продакшн окружение

## Ключевые улучшения

### Безопасность
- Multi-stage сборка для минимизации размера образа
- Непривилегированный пользователь в контейнерах
- Отключены опасные PHP функции в продакшне
- Security headers в Nginx
- Изоляция сетей

### Производительность
- OPcache оптимизация для продакшна
- Gzip сжатие
- Кеширование статических файлов
- Оптимизированные настройки PHP-FPM

### Управление
- Единый скрипт `./dev.sh` для всех операций
- Автоматическое резервное копирование БД
- Health checks для всех сервисов
- Централизованное логирование

## Миграция с старой конфигурации

### 1. Остановите старые контейнеры

```bash
docker-compose down
docker system prune -a
```

### 2. Удалите старые файлы

Старые файлы Docker конфигурации были удалены:
- `docker-compose.dev.yml`
- `docker-compose.local.yml`
- `docker-compose.prod.yml`
- Директория `docker/` (старая структура)

### 3. Настройте новое окружение

Для локальной разработки:
```bash
cp .env.sail.example .env
# Отредактируйте .env под ваши нужды
./dev.sh up
./dev.sh install
./sail artisan key:generate
./dev.sh reset-db
```

Для продакшна:
```bash
cp .env.production.example .env
# Настройте продакшн параметры
ENV=production ./dev.sh build
ENV=production ./dev.sh deploy
```

## Изменения в командах

| Старая команда | Новая команда |
|----------------|---------------|
| `docker-compose up -d` | `./dev.sh up` |
| `docker-compose down` | `./dev.sh down` |
| `docker-compose exec php php artisan ...` | `./sail artisan ...` |
| `docker-compose exec php composer ...` | `./sail composer ...` |
| `docker-compose exec php npm ...` | `./sail npm ...` |
| `docker exec shop_php php artisan test` | `./dev.sh test` |

## Новые возможности

### Laravel Sail

- Встроенная поддержка Xdebug
- Mailpit для тестирования почты
- Автоматическая настройка прав доступа
- Поддержка всех популярных пакетных менеджеров (npm, pnpm, bun)

### Продакшн окружение

- All-in-one контейнер (Nginx + PHP-FPM)
- Supervisor для управления процессами
- Автоматический запуск очередей и планировщика
- Ежедневное резервное копирование БД

## Структура файлов

```
vedma/
├── docker/
│   ├── production/         # Продакшн конфигурация
│   │   ├── nginx/
│   │   ├── php/
│   │   └── supervisord.conf
│   ├── sail/              # Laravel Sail файлы
│   │   └── 8.3/
│   └── scripts/           # Вспомогательные скрипты
├── docker-compose.yml     # Sail конфигурация
├── docker-compose.production.yml  # Продакшн конфигурация
├── sail                   # Sail CLI
├── dev.sh                # Унифицированный скрипт управления
├── .env.sail.example     # Пример для разработки
└── .env.production.example  # Пример для продакшна
```

## Решение проблем

### Проблемы с правами доступа

Laravel Sail автоматически настраивает права через WWWUSER и WWWGROUP.
Если возникают проблемы:

```bash
# Linux/macOS
export WWWUSER=$(id -u)
export WWWGROUP=$(id -g)
./dev.sh up
```

### Конфликт портов

Если порты заняты, измените их в `.env`:
```env
APP_PORT=8080
FORWARD_DB_PORT=54320
FORWARD_REDIS_PORT=63790
```

### Миграция данных

Для переноса данных из старой БД:
1. Создайте дамп старой БД
2. Импортируйте в новую:
```bash
./sail psql < old_dump.sql
```

## Рекомендации

1. Всегда используйте `./dev.sh` для управления контейнерами
2. Для продакшна обязательно измените все пароли в `.env`
3. Настройте резервное копирование на внешнее хранилище
4. Используйте CI/CD для автоматического деплоя
5. Мониторьте логи и метрики приложения

## Дополнительная помощь

- [Docker Setup Guide](DOCKER_SETUP.md) - подробная документация
- [Laravel Sail Docs](https://laravel.com/docs/sail)
- Создайте issue в репозитории при возникновении проблем