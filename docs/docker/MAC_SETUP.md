# Docker настройка для macOS

## Обзор

Конфигурация Docker для macOS оптимизирована для решения проблем производительности файловой системы и особенностей macOS. Автоматически используется `docker-compose.local.yml` при запуске на Mac.

## Особенности Mac конфигурации

### 🚀 Оптимизация производительности

1. **Кэширование файловой системы**
   ```yaml
   volumes:
     - ./:/var/www/html:cached  # Кэширование для основных файлов
   ```

2. **Отдельные Docker volumes для зависимостей**
   ```yaml
   volumes:
     - vendor_volume:/var/www/html/vendor
     - node_modules_volume:/var/www/html/node_modules
   ```

3. **Tmpfs для временных файлов**
   ```yaml
   tmpfs:
     - /tmp/php:uid=1000,gid=1000
   ```

### 🔧 Совместимость с macOS

1. **Фиксированные UID/GID**
   - Использует UID: 1000, GID: 1000 для совместимости
   - Автоматическая настройка прав доступа

2. **Специальный entrypoint для nginx**
   - Обработка прав доступа для Mac
   - Совместимость с Docker Desktop for Mac

## Быстрый старт

### 1. Установка зависимостей

Убедитесь, что установлены:
- [Docker Desktop for Mac](https://docs.docker.com/desktop/mac/install/)
- Git

### 2. Клонирование и настройка

```bash
# Клонирование проекта
git clone <repository-url>
cd <project-directory>

# Копирование конфигурации
cp .env.example .env

# Редактирование .env файла
nano .env
```

### 3. Запуск

```bash
# Запуск контейнеров (автоматически определяет Mac)
./dev.sh up

# Проверка состояния
./dev.sh status
```

## Управление зависимостями

### Composer пакеты

На Mac vendor хранится в Docker volume для производительности:

```bash
# Установка пакетов
./dev.sh composer install

# Добавление нового пакета
./dev.sh composer require vendor/package

# Синхронизация vendor volume
./dev.sh sync-vendor
```

### NPM пакеты

```bash
# Установка через контейнер
./dev.sh shell php
npm install

# Или напрямую если Node.js установлен локально
npm install
```

## Конфигурационные отличия

### Docker Compose (`docker-compose.local.yml`)

```yaml
services:
  nginx:
    container_name: shop_nginx_mac  # Отдельные имена для Mac
    volumes:
      - ./public:/var/www/html/public:cached  # Кэширование
    environment:
      APP_ENV: local

  php:
    container_name: shop_php_mac
    volumes:
      - ./:/var/www/html:cached
      - vendor_volume:/var/www/html/vendor  # Отдельный volume
      - node_modules_volume:/var/www/html/node_modules
    deploy:
      resources:
        limits:
          cpus: '1.5'  # Больше ресурсов для Mac
          memory: 1G
```

### Nginx entrypoint (`docker/nginx/localMac/entrypoint.sh`)

```bash
# Специальная обработка прав доступа для Mac
if [ "${APP_ENV:-local}" = "local" ]; then
    # Создание директорий
    mkdir -p /var/www/html/public
    
    # Настройка прав для appuser/nginx
    chown -R appuser:appgroup /var/www/html/public
    chmod -R 755 /var/www/html/public
fi
```

## Производительность

### Рекомендуемые настройки Docker Desktop

1. **Resources > Advanced:**
   - CPUs: 4+ cores
   - Memory: 8GB+
   - Disk image size: 64GB+

2. **Resources > File Sharing:**
   - Добавьте директорию проекта в список

3. **Experimental Features:**
   - Включите "Use gRPC FUSE for file sharing"

### Мониторинг производительности

```bash
# Использование ресурсов
docker stats

# Размер volumes
docker system df -v

# Производительность файловой системы
./dev.sh shell php
time ls -la /var/www/html
```

## Troubleshooting

### Медленная работа файловой системы

1. **Используйте volumes для зависимостей:**
   ```bash
   ./dev.sh sync-vendor
   ```

2. **Проверьте настройки Docker Desktop:**
   - Увеличьте выделенную память
   - Включите experimental features

3. **Очистите кэши:**
   ```bash
   ./dev.sh clean
   docker system prune -af
   ```

### Проблемы с правами доступа

1. **Проверьте UID/GID:**
   ```bash
   ./dev.sh shell php
   id  # Должно показать uid=1000 gid=1000
   ```

2. **Пересоздайте контейнеры:**
   ```bash
   ./dev.sh rebuild
   ```

### Ошибки подключения к базе данных

1. **Проверьте health check:**
   ```bash
   ./dev.sh status
   docker-compose -f docker-compose.local.yml ps
   ```

2. **Проверьте логи:**
   ```bash
   ./dev.sh logs mysql
   ```

## Команды для Mac

### Основные команды

```bash
# Запуск (автоматически определяет Mac)
./dev.sh up

# Остановка
./dev.sh down

# Полная пересборка
./dev.sh rebuild

# Состояние контейнеров
./dev.sh status
```

### Mac-специфичные команды

```bash
# Синхронизация vendor
./dev.sh sync-vendor

# Проверка безопасности (Mac версия)
./dev.sh security-check

# Подключение к контейнеру
./dev.sh shell php
```

### Разработка

```bash
# Laravel команды
./dev.sh artisan migrate
./dev.sh artisan make:controller TestController

# Composer команды
./dev.sh composer install
./dev.sh composer update

# Тесты и документация
./dev.sh test
./dev.sh docs
./dev.sh lint
```

## Сравнение с Linux версией

| Аспект | Mac (local.yml) | Linux (dev.yml) |
|---------|-----------------|-----------------|
| Файловая система | Cached bind mounts | Обычные bind mounts |
| Vendor/node_modules | Docker volumes | Bind mounts |
| Имена контейнеров | shop_*_mac | shop_*_dev |
| Ресурсы CPU | 1.5 cores | 1.0 cores |
| Ресурсы RAM | 1GB | 512MB |
| Подсеть | 172.21.0.0/16 | 172.20.0.0/16 |

## Лучшие практики для Mac

1. **Используйте volumes для зависимостей**
   - Vendor, node_modules в отдельных volumes
   - Значительно улучшает производительность

2. **Настройте Docker Desktop правильно**
   - Выделите достаточно ресурсов
   - Включите экспериментальные функции

3. **Регулярно очищайте ресурсы**
   ```bash
   ./dev.sh clean
   ```

4. **Мониторьте производительность**
   ```bash
   docker stats
   ```

5. **Используйте sync-vendor после изменения зависимостей**
   ```bash
   ./dev.sh sync-vendor
   ```

## Безопасность на Mac

Все настройки безопасности из Linux версии применены и для Mac:

- ✅ Непривилегированные пользователи
- ✅ Ограничения capabilities
- ✅ Контроль ресурсов
- ✅ Tmpfs для временных файлов
- ✅ Security headers в nginx
- ✅ Rate limiting

```bash
# Проверка безопасности
./dev.sh security-check
```

## Поддержка

При возникновении проблем:

1. Проверьте логи: `./dev.sh logs`
2. Проверьте состояние: `./dev.sh status`
3. Попробуйте пересборку: `./dev.sh rebuild`
4. Очистите ресурсы: `./dev.sh clean`

Для получения справки по всем доступным командам:
```bash
./dev.sh help
```