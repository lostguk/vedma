# Production runbook

## Цель

Production backend деплоится из ветки `main` и занимает текущий dev-слот backend на сервере: каталог `/opt/vedma-dev`, внешний порт `8000`, основной домен через существующий reverse proxy.

Dev-окружение не переносится в production: production-образ запекает код и зависимости внутрь Docker image, не использует bind mount всего проекта и работает с `APP_ENV=production`.

## Production `.env`

На сервере файл `.env` не коммитится. Перед первым запуском проверьте значения без вывода секретов:

```bash
test -n "$APP_KEY"
test "$APP_ENV" = "production"
test "$APP_DEBUG" = "false"
test -n "$APP_URL"
test -n "$FRONTEND_URL"
test -n "$DB_DATABASE"
test -n "$DB_USERNAME"
test -n "$DB_PASSWORD"
test -n "$REDIS_PASSWORD"
```

Минимальные production-переменные:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vedminozelie.ru
FRONTEND_URL=https://vedminozelie.ru
APP_PUBLISHED_PORT=8000

DB_HOST=mysql
REDIS_HOST=redis
REDIS_PASSWORD=<secure-password>

LOG_LEVEL=error
TELESCOPE_ENABLED=false
SESSION_SECURE_COOKIE=true
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

Если production должен использовать текущие dev-данные, задайте volume names из существующих dev-контейнеров:

```bash
docker inspect vedma_dev_mysql --format '{{range .Mounts}}{{if eq .Destination "/var/lib/mysql"}}{{.Name}}{{end}}{{end}}'
docker inspect vedma_dev_redis --format '{{range .Mounts}}{{if eq .Destination "/data"}}{{.Name}}{{end}}{{end}}'
```

Эти значения можно записать в `.env`:

```dotenv
MYSQL_VOLUME_NAME=<existing-dev-mysql-volume>
REDIS_VOLUME_NAME=<existing-dev-redis-volume>
```

GitHub Actions делает такое определение автоматически при cutover из `/opt/vedma-dev`.

## Deploy flow

Production workflow [`deploy.yml`](../../.github/workflows/deploy.yml) выполняется при push в `main`:

1. Подключается к серверу по SSH.
2. Переходит в `/opt/vedma-dev`.
3. Делает backup текущей MySQL и `storage/app`.
4. Переключает рабочую копию на `main`.
5. Останавливает текущий dev compose.
6. Собирает production image с Docker cache или `--no-cache`, если менялись Docker/Composer/NPM файлы.
7. Поднимает `docker-compose.production.yml` на внешнем порту `8000`.
8. Выполняет `storage:link`, `migrate --force`, `optimize:clear`, `config:cache`, `route:cache`, `view:cache`, `queue:restart`.
9. Проверяет Docker health и HTTP `/health`.
10. Очищает старые Docker images старше 72 часов.

## Ручной запуск

На сервере:

```bash
cd /opt/vedma-dev
./dev.sh prod-deploy
```

Для принудительной полной пересборки:

```bash
cd /opt/vedma-dev
./dev.sh prod-deploy --no-cache
```

Отдельные шаги:

```bash
./dev.sh prod-build
./dev.sh prod-up
./dev.sh prod-migrate
./dev.sh prod-optimize
./dev.sh prod-health
```

## Smoke checks

```bash
docker compose -f docker-compose.production.yml ps
curl -fsS http://127.0.0.1:8000/health
docker compose -f docker-compose.production.yml exec -T app php artisan migrate:status
docker compose -f docker-compose.production.yml exec -T app php artisan route:list --path=api
docker compose -f docker-compose.production.yml exec -T app php artisan tinker --execute="Redis::connection()->ping();"
```

Проверка ассетов админки:

```bash
docker compose -f docker-compose.production.yml exec -T app test -f public/build/manifest.json
```

## Rollback

Если production не поднялся:

```bash
cd /opt/vedma-dev
docker compose -f docker-compose.production.yml logs --tail=200 app
docker compose -f docker-compose.production.yml down
git checkout dev
docker compose -f docker-compose.dev.yml up -d --no-build
```

Backup создается в `/opt/vedma-backups/backend/<timestamp>`.

Для восстановления базы из SQL backup сначала остановите приложение, затем выполните импорт в нужный MySQL-контейнер. Не используйте `migrate:fresh` или `reset-db` на production.

## Важные ограничения

- MySQL и Redis в production compose не публикуются наружу, они доступны только внутри Docker network.
- Redis password задается через `REDIS_PASSWORD`, секретов в `docker/redis/redis.conf` быть не должно.
- Production deploy использует только `php artisan migrate --force`; destructive reset-команды запрещены.
- `TELESCOPE_ENABLED=false` обязателен для production, если нет отдельной защищенной observability-схемы.
