# 🔒 Руководство по безопасности Docker конфигурации

## Обзор безопасности

Данная Docker конфигурация разработана в соответствии с европейскими стандартами безопасности, включая требования GDPR, и следует лучшим практикам контейнеризации.

## 🛡️ Реализованные меры безопасности

### 1. Изоляция контейнеров

- **Непривилегированные контейнеры**: Все контейнеры запускаются с флагом `no-new-privileges:true`
- **Ограничение capabilities**: Используется `cap_drop: ALL` с добавлением только необходимых прав
- **Сетевая изоляция**: Разделение frontend и backend сетей
- **Read-only файловые системы**: Где возможно, используются read-only volumes

### 2. Управление ресурсами

- **Ограничения CPU и памяти**: Все сервисы имеют четкие лимиты ресурсов
- **tmpfs для временных файлов**: Использование tmpfs для /tmp директорий
- **Ограничения подключений**: Rate limiting в nginx и ограничения в MySQL

### 3. Безопасность PHP

- **Отключенные опасные функции**: Расширенный список отключенных PHP функций
- **Session безопасность**: Secure cookies, HttpOnly, SameSite настройки
- **Ограничения директорий**: open_basedir для ограничения доступа к файлам
- **Скрытие версий**: Отключение expose_php и server_tokens

### 4. Безопасность базы данных

- **Использование секретов**: Пароли хранятся в файлах секретов
- **Ограничения подключений**: max_connections, max_user_connections
- **Аудит и логирование**: Включено логирование медленных запросов
- **Привязка к localhost**: Доступ только с localhost в dev режиме

### 5. Nginx безопасность

- **Security headers**: Полный набор заголовков безопасности
- **Rate limiting**: Ограничения на API и auth endpoints
- **SSL/TLS**: Современные протоколы и шифры
- **CSP**: Content Security Policy для защиты от XSS
- **OCSP Stapling**: Для оптимизации SSL

## 🔧 Конфигурация окружений

### Development Environment

Файл: `docker-compose.dev.yml`

**Особенности:**
- Менее строгие ограничения для удобства разработки
- Доступ к Adminer для работы с БД
- Расширенное логирование и отладка
- Больше лимитов ресурсов для разработки

**Порты:**
- Web: `8000` (HTTP only)
- MySQL: `3307` (localhost only)
- Redis: `6378` (localhost only)
- Adminer: `8081` (localhost only)

### Production Environment

Файл: `docker-compose.yml`

**Особенности:**
- Максимальные меры безопасности
- Использование секретов для паролей
- Read-only volumes где возможно
- Изолированная backend сеть
- SSL/TLS обязательно

**Порты:**
- Web: `80`, `443` (HTTPS redirect)
- MySQL: `3306` (localhost only)
- Redis: `6379` (localhost only)

## 🔑 Управление секретами

### Создание секретов

```bash
# Создание безопасных паролей
mkdir -p secrets
echo "$(openssl rand -base64 32)" > secrets/mysql_root_password.txt
echo "$(openssl rand -base64 32)" > secrets/mysql_password.txt
chmod 600 secrets/*
```

### Переменные окружения

**Обязательные для production:**
```env
DB_PASSWORD=use_secrets_instead
REDIS_PASSWORD=strong_redis_password
APP_ENV=production
APP_DEBUG=false
```

## 🚀 Использование

### Запуск development окружения

```bash
./dev.sh dev-up
```

### Запуск production окружения

```bash
./dev.sh prod-up
```

### Проверка безопасности

```bash
./dev.sh security-scan
```

## 📋 Чек-лист безопасности

### Перед развертыванием в продакшене:

- [ ] Изменены все пароли по умолчанию
- [ ] Настроены SSL сертификаты
- [ ] Проверены права доступа к файлам (600 для .env, secrets)
- [ ] Настроены переменные окружения
- [ ] Выполнена проверка безопасности (`./dev.sh security-scan`)
- [ ] Настроено резервное копирование
- [ ] Настроен мониторинг логов
- [ ] Проверены network политики
- [ ] Настроен firewall на хост системе

### Регулярное обслуживание:

- [ ] Обновление Docker образов
- [ ] Ротация паролей и секретов
- [ ] Проверка логов безопасности
- [ ] Обновление SSL сертификатов
- [ ] Аудит зависимостей (`composer audit`)
- [ ] Резервное копирование данных

## 🛠️ Мониторинг и логирование

### Просмотр логов

```bash
# Все логи
./dev.sh logs

# Логи конкретного сервиса
./dev.sh logs nginx
./dev.sh logs php
./dev.sh logs mysql
```

### Мониторинг ресурсов

```bash
# Статус системы
./dev.sh status

# Использование ресурсов Docker
docker stats
```

## 🚨 Инциденты безопасности

### При обнаружении проблемы:

1. **Немедленно остановите скомпрометированные контейнеры**
   ```bash
   ./dev.sh down
   ```

2. **Соберите логи для анализа**
   ```bash
   docker-compose logs > incident_$(date +%Y%m%d_%H%M%S).log
   ```

3. **Измените все пароли и секреты**
4. **Проверьте целостность данных**
5. **Обновите все компоненты до последних версий**

## 📞 Контакты

- **Администратор безопасности**: security@company.com
- **DevOps команда**: devops@company.com
- **Техническая поддержка**: support@company.com

## 📚 Дополнительные ресурсы

- [Docker Security Best Practices](https://docs.docker.com/engine/security/)
- [OWASP Container Security](https://owasp.org/www-project-container-security/)
- [CIS Docker Benchmark](https://www.cisecurity.org/benchmark/docker)
- [GDPR Compliance Guide](https://gdpr.eu/)

---

**Важно:** Данная конфигурация обеспечивает высокий уровень безопасности, но безопасность - это процесс, требующий постоянного внимания и обновлений.