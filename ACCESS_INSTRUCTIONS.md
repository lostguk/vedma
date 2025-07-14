# 🌐 Доступ к проекту через браузер

## 📋 Текущий статус

**✅ Проект работает локально:**
- Адрес: http://localhost:3000
- Статус: Полностью функционален
- Все тесты: 61/61 ✅

**🌐 ДОСТУПЕН ЧЕРЕЗ CLOUDFLARE TUNNEL:**
- **Публичный URL: https://slip-seq-sport-academics.trycloudflare.com**
- **Статус: Активен и работает** ✅
- **Health Check: https://slip-seq-sport-academics.trycloudflare.com/health**

**❌ Прямой внешний доступ заблокирован:**
- Внешний IP: 52.20.19.128
- Порты заблокированы AWS Security Group/Firewall

## 🔧 Варианты доступа к проекту:

### 1. **SSH Туннель (Рекомендуется)**

Если у вас есть SSH доступ к серверу:

```bash
# Создайте SSH туннель с локального порта 3000 на удаленный порт 3000
ssh -L 3000:localhost:3000 user@52.20.19.128

# Затем откройте в браузере:
# http://localhost:3000
```

### 2. **Открытие порта в AWS Security Group**

Если у вас есть доступ к AWS Console:

1. Откройте AWS EC2 Console
2. Найдите ваш инстанс (IP: 52.20.19.128)
3. Перейдите в Security Groups
4. Добавьте правило:
   - Type: Custom TCP
   - Port: 3000
   - Source: 0.0.0.0/0 (или ваш IP)
5. Сохраните изменения

**После этого проект будет доступен по адресу:**
```
http://52.20.19.128:3000
```

### 3. **Использование ngrok (Альтернатива)**

Если нет доступа к AWS:

```bash
# Установите ngrok
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null
echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list
sudo apt update && sudo apt install ngrok

# Запустите туннель
ngrok http 3000

# Используйте полученный URL для доступа
```

### 4. **Cloudflare Tunnel**

```bash
# Установите cloudflared
wget -q https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64
chmod +x cloudflared-linux-amd64
sudo mv cloudflared-linux-amd64 /usr/local/bin/cloudflared

# Создайте туннель
cloudflared tunnel --url http://localhost:3000
```

## 🧪 Тестирование доступа

После настройки любого из вариантов, проверьте доступ:

### Главная страница:
```json
{
  "message": "Магазин магических товаров API",
  "version": "1.0.0", 
  "status": "working",
  "timestamp": "2025-07-12T07:08:13.300283Z"
}
```

### Health Check:
```
GET /health
Ответ: healthy
```

### API Endpoints:
- `GET /api/categories` - категории товаров
- `GET /api/products` - товары
- `POST /api/auth/register` - регистрация
- `POST /api/auth/login` - вход

## 📱 Команды для управления

```bash
# Запуск проекта
./dev.sh up

# Остановка проекта  
./dev.sh down

# Статус контейнеров
./dev.sh status

# Запуск тестов
./dev.sh test

# Сброс базы данных
./dev.sh reset-db
```

## 🔍 Отладка

Если проект не отвечает:

```bash
# Проверьте статус контейнеров
./dev.sh status

# Посмотрите логи
./vendor/bin/sail logs

# Проверьте локальный доступ
curl http://localhost:3000

# Проверьте health check
curl http://localhost:3000/health
```

---

**Проект полностью настроен и готов к работе! 🚀**