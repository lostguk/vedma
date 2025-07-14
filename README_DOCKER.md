# 🐳 Docker Configuration

Проект использует две Docker конфигурации:
- **Laravel Sail** для локальной разработки
- **Продакшн конфигурация** для развертывания

## 📋 Требования

- Docker 20.10+
- Docker Compose 2.0+
- Минимум 4GB RAM
- Минимум 10GB свободного места

## 🔧 Настройка

### 1. Подготовка окружения

```bash
# Копируем конфигурацию
cp .env.example .env

# Настраиваем переменные в .env
# Основные переменные для разработки уже настроены
```

### 2. Установка зависимостей

Если у вас нет PHP локально, установите зависимости через Docker:

```bash
# Устанавливаем Composer зависимости
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install

# Делаем скрипт исполняемым
chmod +x dev.sh
```

## 🚀 Локальная разработка (Laravel Sail)

### Первый запуск

```bash
# Запуск окружения
./dev.sh up

# Генерация ключа приложения (если нужно)
./dev.sh artisan key:generate

# Миграции и сиды
./dev.sh reset-db

# Создание символической ссылки для storage
./dev.sh artisan storage:link
```

### Ежедневная работа

```bash
# Запуск/остановка
./dev.sh up
./dev.sh down
./dev.sh restart

# Работа с базой
./dev.sh reset-db           # Сброс БД + сиды
./dev.sh artisan migrate    # Только миграции

# Разработка
./dev.sh test               # Тесты
./dev.sh lint               # Форматирование кода
./dev.sh docs               # Генерация API документации
./dev.sh cache              # Очистка кэшей

# Консоль и логи
./dev.sh shell              # Консоль PHP контейнера
./dev.sh logs               # Логи приложения
./dev.sh status             # Статус контейнеров
```

### Доступ к сервисам

- **Приложение:** http://localhost
- **Adminer (БД):** Устанавливается отдельно при необходимости
- **MySQL:** localhost:3306
- **Redis:** localhost:6379
- **Telescope:** http://localhost/telescope

### Работа с пакетами

```bash
# Composer
./dev.sh composer install
./dev.sh composer require package/name

# NPM
./dev.sh npm install
./dev.sh npm run dev
./dev.sh npm run build

# Artisan
./dev.sh artisan make:controller TestController
./dev.sh artisan queue:work
```

## 🏭 Продакшн

### Сборка образа

```bash
# Сборка продакшн образа
./dev.sh prod-build
```

### Запуск продакшн окружения

```bash
# Настройте .env для продакшна
# APP_ENV=production
# APP_DEBUG=false
# DB_HOST=mysql
# REDIS_HOST=redis

# Запуск
./dev.sh prod-up

# Логи
./dev.sh prod-logs          # Все сервисы
./dev.sh prod-logs app      # Приложение
./dev.sh prod-logs mysql    # База данных
./dev.sh prod-logs redis    # Redis
```

### Доступ к продакшн сервисам

- **Приложение:** http://localhost:8080
- **MySQL:** localhost:3306
- **Redis:** localhost:6379

### Остановка продакшн

```bash
./dev.sh prod-down
```

## 🔒 Безопасность

### Разработка (Sail)

- Используется официальные образы Laravel Sail
- Автоматическое управление пользователями
- Изолированная сеть
- Локальные volumes для данных

### Продакшн

- ✅ Non-root пользователь (www)
- ✅ Минимальные capabilities
- ✅ Read-only файловая система где возможно
- ✅ No new privileges
- ✅ Скрытие версий серверов
- ✅ Безопасные заголовки HTTP
- ✅ Ограничения ресурсов
- ✅ Health checks

## 📊 Мониторинг

### Логи

```bash
# Разработка
./dev.sh logs               # Все логи Sail
./dev.sh logs mysql         # Логи MySQL

# Продакшн
./dev.sh prod-logs          # Все логи продакшн
./dev.sh prod-logs app      # Логи приложения
```

### Health Checks

Продакшн контейнеры имеют встроенные health checks:

- **App:** `GET /health`
- **MySQL:** `mysqladmin ping`
- **Redis:** `redis-cli ping`

### Статус

```bash
./dev.sh status             # Статус всех окружений
```

## 🔧 Конфигурации

### Структура файлов

```
├── docker-compose.yml              # Laravel Sail (разработка)
├── docker-compose.production.yml   # Продакшн
├── Dockerfile                      # Продакшн образ
├── docker/                         # Конфигурации для продакшн
│   ├── nginx/
│   │   ├── nginx.conf
│   │   └── default.conf
│   ├── php/
│   │   ├── php.ini
│   │   └── php-fpm.conf
│   ├── mysql/
│   │   └── my.cnf
│   ├── redis/
│   │   └── redis.conf
│   └── supervisor/
│       └── supervisord.conf
└── dev.sh                          # Управляющий скрипт
```

### Переменные окружения

**Разработка (Sail):**
- `APP_PORT=80` - порт приложения
- `FORWARD_DB_PORT=3306` - порт MySQL
- `FORWARD_REDIS_PORT=6379` - порт Redis
- `WWWUSER/WWWGROUP` - автоматически

**Продакшн:**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_HOST=mysql`
- `REDIS_HOST=redis`

## 🐛 Устранение неполадок

### Общие проблемы

1. **Docker не запущен**
   ```bash
   sudo systemctl start docker
   ```

2. **Порты заняты**
   ```bash
   # Проверьте какие порты используются
   sudo netstat -tulpn | grep :80
   
   # Измените порты в .env
   APP_PORT=8080
   ```

3. **Права доступа (Linux)**
   ```bash
   # Убедитесь что ваш пользователь в группе docker
   sudo usermod -aG docker $USER
   
   # Перелогиньтесь или выполните
   newgrp docker
   ```

4. **Медленная работа на Mac**
   - Sail автоматически оптимизирован для Mac
   - Используются named volumes для vendor/
   - Настроено кэширование файловой системы

### Очистка

```bash
# Очистка всех Docker ресурсов
./dev.sh docker-clean

# Ручная очистка
docker system prune -af --volumes
```

### Сброс конфигурации

```bash
# Полный сброс разработки
./dev.sh down
./dev.sh build
./dev.sh up
./dev.sh reset-db

# Полный сброс продакшн
./dev.sh prod-down
./dev.sh prod-build
./dev.sh prod-up
```

## 📚 Дополнительные команды

```bash
# IDE Helper (для автодополнения)
./dev.sh ide-helper

# Работа с очередями (в отдельном терминале)
./dev.sh artisan queue:work

# Планировщик задач (в отдельном терминале)
./dev.sh artisan schedule:work

# Подключение к базе данных
./dev.sh artisan tinker
```

## 🔄 Миграция с старой конфигурации

Если у вас была старая Docker конфигурация:

```bash
# 1. Остановите старые контейнеры
docker-compose down

# 2. Удалите старые образы (опционально)
docker images | grep shop_ | awk '{print $3}' | xargs docker rmi

# 3. Очистите volumes (ВНИМАНИЕ: потеря данных!)
docker volume prune

# 4. Следуйте инструкциям выше для новой настройки
```

---

**💡 Совет:** Используйте `./dev.sh help` для быстрого просмотра всех доступных команд!