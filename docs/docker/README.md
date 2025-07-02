# Docker конфигурация для разработки

## Обзор

Проект использует Docker для обеспечения изолированной и воспроизводимой среды разработки. Конфигурация следует лучшим практикам безопасности и производительности.

## Архитектура

### Контейнеры

1. **shop_nginx_dev** - Веб-сервер

    - Базовый образ: `nginx:alpine`
    - Порт: 8000
    - Пользователь: `appuser` (UID: 1000)
    - Read-only файловая система
    - Ограничения ресурсов: 0.5 CPU, 128MB RAM

2. **shop_php_dev** - PHP-FPM

    - Базовый образ: `php:8.3-fpm`
    - Порт: 9001
    - Пользователь: `appuser` (UID: 1000)
    - Ограничения ресурсов: 1.0 CPU, 512MB RAM

3. **shop_mysql_dev** - База данных

    - Базовый образ: `mysql:8.0`
    - Порт: 3307
    - Ограничения ресурсов: 1.0 CPU, 1GB RAM

4. **shop_redis_dev** - Кэш

    - Базовый образ: `redis:alpine`
    - Порт: 6378
    - Ограничения ресурсов: 0.5 CPU, 256MB RAM

5. **shop_adminer_dev** - Веб-интерфейс БД
    - Базовый образ: `adminer:latest`
    - Порт: 8081

## Безопасность

### Принципы безопасности

1. **Принцип наименьших привилегий**

    - Все контейнеры запускаются под непривилегированными пользователями
    - Минимальный набор capabilities
    - `no-new-privileges:true` для всех контейнеров

2. **Изоляция ресурсов**

    - Ограничения CPU и памяти для каждого контейнера
    - Использование tmpfs для временных файлов
    - Read-only volumes где это возможно

3. **Сетевая безопасность**
    - Изолированная сеть Docker
    - Контроль портов
    - Rate limiting на уровне nginx

### Настройки безопасности

#### Nginx

-   Скрытие версии сервера
-   Защитные заголовки (CSP, XSS Protection, etc.)
-   Rate limiting для API endpoints
-   Блокировка доступа к чувствительным файлам

#### PHP-FPM

-   Ограничение типов файлов (.php только)
-   Безопасная конфигурация open_basedir
-   Отключение опасных функций
-   Контроль загрузки файлов

#### MySQL

-   Непривилегированный пользователь
-   Ограничение подключений
-   Безопасная конфигурация

## Использование

### Быстрый старт

```bash
# Запуск всех контейнеров
./dev.sh up

# Остановка контейнеров
./dev.sh down

# Полная пересборка
./dev.sh rebuild
```

### Управление базой данных

```bash
# Сброс базы данных и загрузка сидов
./dev.sh reset-db

# Подключение к MySQL
mysql -h localhost -P 3307 -u ${DB_USERNAME} -p
```

### Разработка

```bash
# Запуск тестов
./dev.sh test

# Генерация документации
./dev.sh docs

# Форматирование кода
./dev.sh lint

# Просмотр логов
./dev.sh logs [service]

# Консоль контейнера
./dev.sh shell [service]
```

### Artisan и Composer

```bash
# Выполнение Artisan команд
./dev.sh artisan migrate
./dev.sh artisan make:controller TestController

# Выполнение Composer команд
./dev.sh composer install
./dev.sh composer require package/name
```

## Конфигурационные файлы

### Структура

```
docker/
├── nginx/
│   ├── Dockerfile
│   ├── nginx.conf              # Базовая конфигурация nginx
│   └── dev/
│       └── conf.d/
│           └── default.conf    # Dev конфигурация
├── php/
│   ├── Dockerfile
│   ├── php.ini                 # Безопасная конфигурация PHP
│   ├── fpm-pool.conf          # Конфигурация FPM pool
│   └── docker-entrypoint.sh   # Инициализационный скрипт
└── mysql/
    └── my.cnf                  # Конфигурация MySQL
```

### Переменные окружения

Скопируйте `.env.example` в `.env` и настройте:

```bash
# Базовые настройки
APP_ENV=local
APP_DEBUG=true

# База данных
DB_DATABASE=shop_db
DB_USERNAME=shop_user
DB_PASSWORD=secure_password

# Пользователь хоста (автоматически)
HOST_UID=1000
HOST_GID=1000
```

## Мониторинг и диагностика

### Проверка состояния

```bash
# Состояние контейнеров
./dev.sh status

# Health check
docker-compose -f docker-compose.dev.yml ps

# Использование ресурсов
docker stats
```

### Проверка безопасности

```bash
# Автоматическая проверка безопасности
./dev.sh security-check

# Ручная проверка пользователей в контейнерах
docker exec shop_nginx_dev whoami
docker exec shop_php_dev whoami
```

### Логи

```bash
# Все логи
./dev.sh logs

# Логи конкретного сервиса
./dev.sh logs nginx
./dev.sh logs php
./dev.sh logs mysql
```

## Производительность

### Оптимизация

1. **Nginx**

    - Gzip сжатие
    - Кэширование статических файлов
    - Оптимизация буферов

2. **PHP-FPM**

    - Настройка pool manager
    - Оптимизация буферов FastCGI
    - Контроль процессов

3. **MySQL**
    - InnoDB оптимизация
    - Настройка буферов
    - Индексы

### Мониторинг ресурсов

```bash
# Использование CPU и памяти
docker stats --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}"

# Размер образов
docker images --format "table {{.Repository}}\t{{.Tag}}\t{{.Size}}"
```

## Troubleshooting

### Частые проблемы

1. **Ошибка подключения nginx к PHP**

    - Проверьте, что PHP-FPM слушает 0.0.0.0:9000
    - Убедитесь, что контейнеры в одной сети

2. **Проблемы с правами доступа**

    - Проверьте HOST_UID и HOST_GID в .env
    - Убедитесь, что пользователи созданы корректно

3. **Ошибки базы данных**
    - Проверьте health check MySQL
    - Убедитесь в корректности переменных окружения

### Команды диагностики

```bash
# Проверка сети Docker
docker network ls
docker network inspect shop_network

# Проверка томов
docker volume ls
docker volume inspect shop_mysql_data

# Подробная информация о контейнере
docker inspect shop_php_dev
```

## Продакшн

Для продакшн окружения используйте:

```bash
# Продакшн конфигурация
docker-compose -f docker-compose.yml up -d

# Или для продакшн сервера
docker-compose -f docker-compose.prod.yml up -d
```

### Отличия продакшн конфигурации

-   SSL сертификаты
-   Более строгие настройки безопасности
-   Оптимизация производительности
-   Логирование в внешние системы
-   Мониторинг и алерты

## Лучшие практики

1. **Регулярно обновляйте базовые образы**
2. **Используйте .dockerignore для оптимизации сборки**
3. **Проверяйте логи на ошибки безопасности**
4. **Мониторьте использование ресурсов**
5. **Делайте резервные копии данных**
6. **Тестируйте конфигурацию перед продакшн**

## Дополнительная информация

-   [Docker Security](https://docs.docker.com/engine/security/)
-   [Nginx Security](https://nginx.org/en/docs/http/request_processing.html)
-   [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.configuration.php)
-   [MySQL Security](https://dev.mysql.com/doc/refman/8.0/en/security.html)
