# 🛍️ Laravel E-commerce Platform

Современная платформа электронной коммерции, построенная на Laravel 11 с Docker контейнеризацией и повышенной безопасностью.

## 🔒 Безопасность

Данный проект реализует европейские стандарты безопасности, включая требования GDPR. Подробная информация доступна в [SECURITY.md](SECURITY.md).

## 🚀 Быстрый старт

### Требования

- Docker 20.10+
- Docker Compose 2.0+
- Git

### Установка для разработки

```bash
# Клонирование репозитория
git clone <repository-url>
cd <project-directory>

# Запуск development окружения
./dev.sh dev-up
```

Приложение будет доступно по адресу: http://localhost:8000

### Панель администратора

Доступна через Filament по адресу: http://localhost:8000/admin

### База данных

- **Adminer**: http://localhost:8081
- **Сервер**: mysql  
- **Пользователь**: из переменной DB_USERNAME
- **Пароль**: из переменной DB_PASSWORD

## 🛠️ Команды управления

Используйте скрипт `./dev.sh` для управления проектом:

```bash
./dev.sh dev-up          # Запуск development окружения
./dev.sh prod-up         # Запуск production окружения  
./dev.sh down            # Остановка контейнеров
./dev.sh reset-db        # Сброс БД с сидами
./dev.sh test            # Запуск тестов
./dev.sh docs            # Генерация API документации
./dev.sh security-scan   # Проверка безопасности
./dev.sh help            # Полный список команд
```

## 🏗️ Архитектура

### Docker Services

- **nginx**: Веб-сервер с SSL/TLS и security headers
- **php**: PHP-FPM 8.3 с расширенными настройками безопасности
- **mysql**: MySQL 8.0 с аудитом и ограничениями
- **redis**: Redis для кэширования и сессий
- **adminer**: Веб-интерфейс для управления БД (только dev)

### Безопасность

- 🔐 Изоляция контейнеров с минимальными привилегиями
- 🚫 Отключенные опасные PHP функции
- 🔒 Защищенные сессии и cookies (GDPR-compliant)
- 🛡️ Rate limiting и DDoS защита
- 📊 Ограничение ресурсов контейнеров
- 🔍 Логирование и мониторинг безопасности

## 📚 API Документация

API документация автоматически генерируется с помощью Scribe:
- Development: http://localhost:8000/docs
- После генерации: `./dev.sh docs`

## 🧪 Тестирование

```bash
# Запуск всех тестов
./dev.sh test

# Внутри контейнера
docker-compose -f docker-compose.dev.yml exec php php artisan test
```

## 🗄️ База данных

### Миграции

```bash
# Сброс БД с сидами
./dev.sh reset-db

# Миграции вручную
docker-compose -f docker-compose.dev.yml exec php php artisan migrate
```

### Сиды

```bash
# Все сиды
docker-compose -f docker-compose.dev.yml exec php php artisan db:seed

# Конкретный сид
docker-compose -f docker-compose.dev.yml exec php php artisan db:seed --class=UserSeeder
```

## 🔧 Разработка

### Форматирование кода

```bash
./dev.sh lint
```

### IDE Helper

```bash
./dev.sh ide-helper
```

### Логи

```bash
# Все логи
./dev.sh logs

# Конкретный сервис
./dev.sh logs php
./dev.sh logs nginx
```

## 🚀 Продакшен

### Перед развертыванием

1. **Измените секреты**:
   ```bash
   echo "$(openssl rand -base64 32)" > secrets/mysql_root_password.txt
   echo "$(openssl rand -base64 32)" > secrets/mysql_password.txt
   chmod 600 secrets/*
   ```

2. **Настройте переменные окружения** в `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   DB_PASSWORD=use_secrets_instead
   REDIS_PASSWORD=strong_password_here
   ```

3. **Настройте SSL сертификаты** в `/etc/letsencrypt/`

4. **Выполните проверку безопасности**:
   ```bash
   ./dev.sh security-scan
   ```

### Запуск в продакшене

```bash
./dev.sh prod-up
```

## 📊 Мониторинг

### Статус системы

```bash
./dev.sh status
```

### Использование ресурсов

```bash
docker stats
```

### Бэкап базы данных

```bash
./dev.sh backup-db
```

## 🛡️ Безопасность в продакшене

### Обязательные настройки

- [ ] Изменить все пароли по умолчанию
- [ ] Настроить SSL/TLS сертификаты
- [ ] Настроить firewall на хост системе
- [ ] Ограничить доступ к портам базы данных
- [ ] Настроить регулярное резервное копирование
- [ ] Настроить мониторинг логов безопасности

### Регулярное обслуживание

- [ ] Обновление Docker образов
- [ ] Ротация паролей и ключей
- [ ] Аудит зависимостей (`composer audit`)
- [ ] Проверка SSL сертификатов
- [ ] Анализ логов безопасности

## 🔍 Отладка

### Логи приложения

```bash
# Логи Laravel
docker-compose -f docker-compose.dev.yml exec php tail -f storage/logs/laravel.log

# Логи PHP-FPM
docker-compose -f docker-compose.dev.yml logs -f php

# Логи nginx
docker-compose -f docker-compose.dev.yml logs -f nginx
```

### Доступ к контейнерам

```bash
# PHP контейнер
docker-compose -f docker-compose.dev.yml exec php bash

# MySQL контейнер
docker-compose -f docker-compose.dev.yml exec mysql mysql -u root -p

# Redis контейнер
docker-compose -f docker-compose.dev.yml exec redis redis-cli
```

## 🤝 Участие в разработке

1. Форкните репозиторий
2. Создайте ветку для фичи (`git checkout -b feature/amazing-feature`)
3. Выполните изменения
4. Запустите тесты и линтинг:
   ```bash
   ./dev.sh test
   ./dev.sh lint
   ./dev.sh security-scan
   ```
5. Создайте Pull Request

## 📞 Поддержка

- **Документация**: См. файлы в директории `docs/`
- **Безопасность**: [SECURITY.md](SECURITY.md)
- **API**: http://localhost:8000/docs

## 📄 Лицензия

Этот проект лицензирован под MIT License - см. файл [LICENSE](LICENSE) для деталей.

---

**⚠️ Важно**: Перед использованием в продакшене обязательно прочитайте [SECURITY.md](SECURITY.md) и выполните все необходимые настройки безопасности.
