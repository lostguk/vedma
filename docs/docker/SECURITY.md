# Безопасность Docker конфигурации

## Решение проблемы подключения

### Исходная проблема

```
2025/07/02 03:47:25 [error] 10#10: *8343 connect() failed (111: Connection refused) while connecting to upstream, client: 127.0.0.1, server: localhost, request: "GET / HTTP/1.1", upstream: "fastcgi://172.18.0.3:9000", host: "localhost"
```

### Причина проблемы

PHP-FPM слушал только localhost (127.0.0.1:9000), что делало его недоступным для nginx контейнера.

### Решение

1. **Изменена конфигурация FPM pool** (`docker/php/fmp-pool.conf`):

    ```ini
    listen = 0.0.0.0:9000  # Вместо listen = 9000
    ```

2. **Обновлена конфигурация nginx** для правильного fastcgi_pass

## Принципы безопасности

### 1. Принцип наименьших привилегий

**Реализовано:**

-   Все контейнеры запускаются под непривилегированными пользователями (UID: 1000)
-   Минимальный набор capabilities для каждого контейнера
-   `no-new-privileges:true` для предотвращения эскалации привилегий

**Пример конфигурации:**

```yaml
security_opt:
    - no-new-privileges:true
cap_drop:
    - ALL
cap_add:
    - CHOWN
    - SETGID
    - SETUID
```

### 2. Изоляция ресурсов

**Реализовано:**

-   Ограничения CPU и памяти для каждого контейнера
-   Использование tmpfs для временных файлов
-   Read-only volumes где это возможно
-   Изолированная сеть Docker

**Ограничения ресурсов:**

```yaml
deploy:
    resources:
        limits:
            cpus: "1.0"
            memory: 512M
        reservations:
            cpus: "0.2"
            memory: 256M
```

### 3. Безопасность сети

**Реализовано:**

-   Изолированная подсеть 172.20.0.0/16
-   Контроль доступа к портам
-   Rate limiting на уровне nginx
-   Firewall правила в nginx

## Безопасность по контейнерам

### Nginx контейнер

**Безопасность:**

-   Read-only файловая система
-   Непривилегированный пользователь appuser
-   Скрытие версии сервера
-   Защитные HTTP заголовки
-   Rate limiting для API
-   Блокировка доступа к чувствительным файлам

**Конфигурация безопасности:**

```nginx
# Security headers
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Content-Security-Policy "default-src 'self'..." always;

# Rate limiting
limit_req_zone $binary_remote_addr zone=api:10m rate=100r/m;
```

### PHP контейнер

**Безопасность:**

-   Использование gosu для управления пользователями
-   Ограничение open_basedir
-   Отключение опасных функций PHP
-   Контроль загрузки файлов
-   Безопасная конфигурация FPM pool

**PHP безопасность:**

```ini
# Безопасность
disable_functions = exec,passthru,shell_exec,system,parse_ini_file,show_source
allow_url_fopen = Off
allow_url_include = Off
open_basedir = /var/www/html:/tmp:/var/www/html/storage
```

### MySQL контейнер

**Безопасность:**

-   Непривилегированный пользователь
-   Ограниченные capabilities
-   Безопасная конфигурация
-   Изолированные данные

## Соответствие европейским стандартам

### GDPR Compliance

1. **Защита данных:**

    - Шифрование данных в покое (MySQL InnoDB)
    - Безопасная передача данных (HTTPS в продакшн)
    - Логирование доступа к данным

2. **Право на забвение:**
    - Механизмы удаления данных пользователей
    - Очистка логов и кэшей

### ISO 27001

1. **Управление доступом:**

    - Принцип наименьших привилегий
    - Изоляция сервисов
    - Аудит доступа

2. **Мониторинг безопасности:**
    - Логирование всех операций
    - Health checks контейнеров
    - Мониторинг ресурсов

### PCI DSS (при работе с платежами)

1. **Сетевая безопасность:**

    - Изолированная сеть
    - Firewall правила
    - Ограничения портов

2. **Защита данных:**
    - Шифрование sensitive данных
    - Безопасное хранение
    - Регулярные обновления

## Мониторинг безопасности

### Автоматические проверки

```bash
# Проверка безопасности контейнеров
./dev.sh security-check

# Проверка пользователей
docker exec shop_nginx_dev whoami
docker exec shop_php_dev whoami

# Проверка capabilities
docker inspect shop_php_dev --format='{{.HostConfig.CapAdd}}'
docker inspect shop_php_dev --format='{{.HostConfig.CapDrop}}'
```

### Логирование

```bash
# Логи безопасности nginx
./dev.sh logs nginx | grep -E "(40[0-9]|50[0-9])"

# Логи аутентификации PHP
./dev.sh logs php | grep -i "auth"

# Системные логи контейнеров
docker logs shop_php_dev --since="1h"
```

## Обновления безопасности

### Регулярные задачи

1. **Обновление базовых образов:**

    ```bash
    docker pull nginx:alpine
    docker pull php:8.3-fpm
    docker pull mysql:8.0
    docker pull redis:alpine
    ```

2. **Сканирование уязвимостей:**

    ```bash
    # Используйте инструменты типа Trivy
    trivy image nginx:alpine
    trivy image php:8.3-fpm
    ```

3. **Аудит конфигурации:**
    ```bash
    # Проверка конфигурации Docker
    docker-bench-security
    ```

## Backup и восстановление

### Регулярные бэкапы

```bash
# Бэкап базы данных
docker-compose -f docker-compose.dev.yml exec mysql mysqldump -u root -p${DB_PASSWORD} ${DB_DATABASE} > backup.sql

# Бэкап volume
docker run --rm -v shop_mysql_data:/data -v $(pwd):/backup alpine tar czf /backup/mysql_backup.tar.gz /data
```

### Восстановление

```bash
# Восстановление из SQL дампа
docker-compose -f docker-compose.dev.yml exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} < backup.sql

# Восстановление volume
docker run --rm -v shop_mysql_data:/data -v $(pwd):/backup alpine tar xzf /backup/mysql_backup.tar.gz -C /
```

## Чек-лист безопасности

### Перед продакшн

-   [ ] Все контейнеры запускаются под непривилегированными пользователями
-   [ ] Настроены ограничения ресурсов
-   [ ] Включены security_opt для всех контейнеров
-   [ ] Настроен HTTPS и SSL сертификаты
-   [ ] Отключены отладочные режимы
-   [ ] Настроено логирование в внешние системы
-   [ ] Проведено сканирование на уязвимости
-   [ ] Настроен мониторинг и алерты
-   [ ] Созданы бэкапы
-   [ ] Протестированы процедуры восстановления

### Регулярные проверки

-   [ ] Обновление базовых образов (еженедельно)
-   [ ] Сканирование уязвимостей (еженедельно)
-   [ ] Проверка логов безопасности (ежедневно)
-   [ ] Тестирование бэкапов (ежемесячно)
-   [ ] Аудит конфигурации (ежемесячно)

## Контакты и поддержка

При обнаружении проблем безопасности:

1. Немедленно остановите подозрительные контейнеры
2. Сохраните логи для анализа
3. Уведомите команду безопасности
4. Создайте инцидент в системе отслеживания

## Дополнительные ресурсы

-   [CIS Docker Benchmark](https://www.cisecurity.org/benchmark/docker)
-   [OWASP Container Security](https://owasp.org/www-project-container-security/)
-   [Docker Security Best Practices](https://docs.docker.com/engine/security/security/)
-   [GDPR Compliance Guide](https://gdpr.eu/)
