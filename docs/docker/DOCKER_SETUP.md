# Docker Setup Guide

## Обзор

Проект использует два Docker окружения:
1. **Laravel Sail** - для локальной разработки
2. **Production Docker** - для продакшн развертывания

## Локальная разработка (Laravel Sail)

### Требования
- Docker Desktop 4.0+
- Docker Compose 2.0+
- Git

### Первоначальная настройка

1. Клонируйте репозиторий:
```bash
git clone <repository-url>
cd vedma
```

2. Скопируйте файл окружения:
```bash
cp .env.example .env
```

3. Настройте переменные окружения в `.env`:
```env
APP_NAME=Vedma
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=vedma
DB_USERNAME=sail
DB_PASSWORD=password

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

4. Запустите контейнеры:
```bash
./dev.sh up
```

5. Установите зависимости:
```bash
./dev.sh install
```

6. Сгенерируйте ключ приложения:
```bash
./sail artisan key:generate
```

7. Выполните миграции и сиды:
```bash
./dev.sh reset-db
```

8. Создайте символическую ссылку для storage:
```bash
./sail artisan storage:link
```

### Основные команды

```bash
# Управление контейнерами
./dev.sh up          # Запустить контейнеры
./dev.sh down        # Остановить контейнеры
./dev.sh restart     # Перезапустить контейнеры
./dev.sh ps          # Статус контейнеров
./dev.sh logs        # Просмотр логов

# База данных
./dev.sh reset-db    # Сбросить БД и запустить сиды
./dev.sh migrate     # Выполнить миграции
./dev.sh seed        # Запустить сиды

# Разработка
./dev.sh test        # Запустить тесты
./dev.sh lint        # Форматировать код
./dev.sh docs        # Сгенерировать документацию
./dev.sh bash        # Войти в контейнер

# Кеш
./dev.sh cache       # Очистить все кеши
./dev.sh optimize    # Оптимизировать приложение
```

### Доступные сервисы

- **Приложение**: http://localhost
- **Mailpit**: http://localhost:8025
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

### Отладка с Xdebug

Xdebug настроен и готов к использованию. Настройки для VS Code:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            }
        }
    ]
}
```

## Продакшн развертывание

### Требования
- Docker 20.10+
- Docker Compose 2.0+
- Минимум 2GB RAM
- 10GB свободного места на диске

### Настройка

1. Клонируйте репозиторий на сервер:
```bash
git clone <repository-url>
cd vedma
```

2. Создайте `.env` файл с продакшн настройками:
```env
APP_NAME=Vedma
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=vedma_production
DB_USERNAME=vedma_user
DB_PASSWORD=strong_password_here

REDIS_HOST=redis
REDIS_PASSWORD=redis_password_here
REDIS_PORT=6379

# Настройки почты
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Vedma"
```

3. Соберите и запустите контейнеры:
```bash
ENV=production ./dev.sh build
ENV=production ./dev.sh deploy
```

### Управление продакшн окружением

```bash
# Сборка образа
ENV=production ./dev.sh build

# Развертывание
ENV=production ./dev.sh deploy

# Остановка
ENV=production ./dev.sh down

# Резервное копирование БД
ENV=production ./dev.sh backup

# Просмотр логов
ENV=production ./dev.sh logs
```

### SSL/TLS настройка

Для продакшн окружения рекомендуется использовать обратный прокси (nginx) с SSL сертификатами:

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location / {
        proxy_pass http://localhost:80;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Мониторинг и логи

Логи приложения сохраняются в:
- `./storage/logs/` - Laravel логи
- Docker логи доступны через: `ENV=production ./dev.sh logs`

### Резервное копирование

Автоматическое резервное копирование БД настроено и выполняется ежедневно в 2:00 AM.
Резервные копии сохраняются в `./backups/` и хранятся 7 дней.

Для ручного создания резервной копии:
```bash
ENV=production ./dev.sh backup
```

### Обновление приложения

1. Получите последние изменения:
```bash
git pull origin main
```

2. Пересоберите и разверните:
```bash
ENV=production ./dev.sh deploy
```

## Решение проблем

### Контейнеры не запускаются

1. Проверьте, что Docker запущен:
```bash
docker info
```

2. Проверьте логи:
```bash
./dev.sh logs
```

3. Убедитесь, что порты не заняты:
```bash
netstat -tlnp | grep -E ':(80|5432|6379)'
```

### Ошибки при миграции

1. Проверьте подключение к БД:
```bash
./sail artisan db:show
```

2. Попробуйте сбросить БД:
```bash
./dev.sh reset-db
```

### Проблемы с правами доступа

1. Убедитесь, что директории имеют правильные права:
```bash
chmod -R 775 storage bootstrap/cache
```

2. Проверьте владельца файлов:
```bash
chown -R $USER:$USER .
```

## Безопасность

### Рекомендации для продакшн

1. **Всегда используйте сильные пароли** для БД и Redis
2. **Настройте файрвол** для ограничения доступа к портам
3. **Используйте SSL/TLS** для всех внешних подключений
4. **Регулярно обновляйте** Docker образы и зависимости
5. **Мониторьте логи** на предмет подозрительной активности
6. **Делайте резервные копии** регулярно

### Ограничения безопасности

В продакшн конфигурации:
- Отключены опасные PHP функции (exec, shell_exec и др.)
- Скрыта версия PHP и Nginx
- Настроены security headers
- Ограничен доступ к конфиденциальным файлам
- Используется непривилегированный пользователь

## Дополнительная информация

- [Laravel Sail Documentation](https://laravel.com/docs/sail)
- [Docker Documentation](https://docs.docker.com/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)