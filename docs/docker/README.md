# Docker Configuration для VedmaShop

Проект настроен с двумя Docker окружениями:
- **Development** - Laravel Sail для локальной разработки
- **Production** - Оптимизированные образы для продакшна

## 🚀 Быстрый старт

### Разработка (Laravel Sail)

1. **Подготовка:**
   ```bash
   cp .env.example .env
   ```

2. **Запуск:**
   ```bash
   ./sail up -d
   ```

3. **Инициализация:**
   ```bash
   ./sail artisan key:generate
   ./sail artisan migrate --seed
   ./sail artisan storage:link
   ```

4. **Доступ:**
   - Приложение: http://localhost
   - Mailpit: http://localhost:8025
   - MySQL: localhost:3306
   - Redis: localhost:6379

### Продакшн

1. **Подготовка:**
   ```bash
   cp .env.production .env.production
   # Настройте переменные в .env.production
   ```

2. **Деплой:**
   ```bash
   ./deploy.sh deploy
   ```

## 📁 Структура файлов

```
project/
├── docker/
│   ├── nginx/          # Nginx конфигурация
│   ├── php/            # PHP-FPM конфигурация
│   ├── mysql/          # MySQL конфигурация
│   ├── redis/          # Redis конфигурация
│   └── supervisor/     # Supervisor конфигурация
├── docker-compose.yml      # Development (Sail)
├── docker-compose.prod.yml # Production
├── sail                    # Development CLI
├── deploy.sh              # Production CLI
├── .env.example           # Development environment
└── .env.production        # Production environment template
```

## 🛠 Development окружение (Sail)

### Основные команды

```bash
# Запуск/остановка
./sail up -d
./sail down

# Laravel команды
./sail artisan migrate
./sail artisan tinker
./sail artisan test

# Composer
./sail composer install
./sail composer update

# NPM
./sail npm install
./sail npm run dev

# Доступ к контейнеру
./sail shell
```

### Сервисы

- **laravel.test** - Основное приложение (порт 80)
- **mysql** - База данных (порт 3306)
- **redis** - Кэш и очереди (порт 6379)
- **mailpit** - Email тестирование (порт 8025)

## 🏭 Production окружение

### Архитектура

- **Multi-stage Dockerfile** для оптимизации размера образа
- **Alpine Linux** для минимального размера и безопасности
- **Non-root пользователь** для повышения безопасности
- **Supervisor** для управления процессами
- **Nginx + PHP-FPM** в одном контейнере

### Основные команды

```bash
# Деплой
./deploy.sh deploy

# Мониторинг
./deploy.sh status
./deploy.sh logs
./deploy.sh monitor

# Управление
./deploy.sh stop
./deploy.sh update

# Резервное копирование
./deploy.sh backup
./deploy.sh restore backups/backup_file
```

### Безопасность

#### PHP настройки:
- Отключен `expose_php`
- Ограничены опасные функции
- Настроен OPcache для производительности
- Безопасные настройки сессий

#### Nginx настройки:
- Скрыты версии сервера
- Защита от доступа к sensitive файлам
- Настроено кэширование статических файлов
- Security headers

#### Docker настройки:
- Non-root пользователь (appuser:1000)
- Минимальные привилегии
- Health checks для всех сервисов
- Изолированная сеть

## 🔧 Конфигурация

### Development (.env)

```env
DB_HOST=mysql
REDIS_HOST=redis
MAIL_MAILER=log
APP_DEBUG=true
TELESCOPE_ENABLED=true
```

### Production (.env.production)

```env
APP_ENV=production
APP_DEBUG=false
DB_HOST=mysql
REDIS_HOST=redis
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
TELESCOPE_ENABLED=false
```

## 📊 Мониторинг и логи

### Health checks

Все сервисы имеют health checks:
- **App**: `GET /health`
- **MySQL**: `mysqladmin ping`
- **Redis**: `redis-cli ping`

### Логи

```bash
# Все сервисы
./deploy.sh logs

# Конкретный сервис
./deploy.sh logs app
./deploy.sh logs mysql
./deploy.sh logs redis
```

### Мониторинг

```bash
# Системная информация
./deploy.sh monitor

# Статус контейнеров
./deploy.sh status
```

## 🔄 CI/CD интеграция

### GitHub Actions пример

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Deploy to server
        run: |
          ssh user@server 'cd /app && git pull && ./deploy.sh update'
```

## 🛡 Backup и восстановление

### Автоматический backup

```bash
# Создание резервной копии
./deploy.sh backup

# Файлы сохраняются в:
# - backups/db_backup_TIMESTAMP.sql
# - backups/storage_backup_TIMESTAMP.tar.gz
```

### Восстановление

```bash
# Восстановление из backup
./deploy.sh restore backups/backup_20240101_120000
```

## 🚨 Troubleshooting

### Проблемы с правами

```bash
# Исправление прав на файлы
./sail artisan storage:link
sudo chown -R $USER:$USER storage bootstrap/cache
```

### Проблемы с производительностью

```bash
# Очистка кэшей
./sail artisan cache:clear
./sail artisan config:clear
./sail artisan view:clear

# В продакшне
./deploy.sh logs app | grep -i error
```

### Проблемы с базой данных

```bash
# Development
./sail artisan migrate:fresh --seed

# Production
docker-compose -f docker-compose.prod.yml exec mysql mysql -u root -p
```

## 📈 Оптимизация производительности

### Development
- Используйте Docker Desktop с достаточным объемом RAM (8GB+)
- Настройте файловую синхронизацию для вашей ОС

### Production
- Настройте ресурсы контейнеров через Docker Swarm или Kubernetes
- Используйте внешние сервисы для БД и Redis в масштабируемых решениях
- Настройте CDN для статических файлов

## 🔗 Полезные ссылки

- [Laravel Sail Documentation](https://laravel.com/docs/sail)
- [Docker Best Practices](https://docs.docker.com/develop/best-practices/)
- [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.configuration.php)
- [Nginx Security](https://nginx.org/en/docs/http/securing_http.html)
