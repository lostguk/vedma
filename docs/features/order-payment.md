**Название фичи:**
Оплата заказа через Альфа-Банк

**Цель:**
Позволить пользователю оплатить заказ через платёжную страницу банка и получать актуальный статус оплаты.

**Сущности и поля:**

-   Payment: public_id, order_id, provider, status, amount, currency, external_order_id, payment_url, paid_at, refunded_at

**Права доступа:**

-   Создание платежа и проверка статуса доступны публично по `public_id`
-   Webhook принимает запросы от банка без авторизации

**Сценарии использования:**

-   Клиент создаёт платеж по `order_id` и получает `payment_url`
-   Клиент переходит на платёжную страницу банка
-   Банк уведомляет систему через webhook
-   Клиент запрашивает статус по `public_id`

**Требуемые тесты:**

-   Создание платежа и получение `payment_url`
-   Обновление статуса через эндпоинт статуса
-   Обработка webhook для оплаты
-   Возврат платежа

**Документация:**

-   `POST /api/v1/payments` — создать платеж
-   `GET /api/v1/payments/{public_id}/status` — получить статус
-   `POST /api/v1/payments/{public_id}/refund` — выполнить возврат
-   `POST /api/v1/payments/alfabank/webhook` — webhook Альфа-Банка

**Пример ответа:**

```json
{
    "status": "success",
    "message": "Success",
    "data": {
        "id": "3f6a9f55-2d43-4c63-8f0a-1f4d2d410f1c",
        "order_id": 12,
        "status": "registered",
        "amount": 1990.5,
        "currency": "RUB",
        "payment_url": "https://pay.alfabank.ru/payment/...",
        "paid_at": null,
        "refunded_at": null,
        "created_at": "2026-01-15 10:00:00"
    }
}
```
