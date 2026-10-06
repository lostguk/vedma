**Название фичи:**
Оплата заказа через Т-Банк

**Цель:**
Позволить пользователю оплатить заказ через платёжную страницу банка и получать актуальный статус оплаты.

**Сущности и поля:**

- Payment: public_id, order_id, provider, status, amount, currency, external_order_id, payment_url, paid_at, refunded_at

**Права доступа:**

- Создание платежа и проверка статуса доступны публично по `public_id`
- Webhook принимает запросы от банка без авторизации

**Сценарии использования:**

- Клиент создаёт платеж по `order_id` и получает `payment_url`
- Сумма платежа формируется как `total_price + delivery_price` (если доставка указана)
- Клиент переходит на платёжную страницу банка
- Банк уведомляет систему через webhook
- Клиент запрашивает статус по `public_id`
- При вызове `Init` к переданным `success_url` и `fail_url` сервер **автоматически добавляет** query-параметр `payment=<public_id>`, чтобы фронт на страницах возврата мог вызвать `GET /payments/{public_id}/status`

**Требуемые тесты:**

- Создание платежа и получение `payment_url`
- Обновление статуса через эндпоинт статуса
- Обработка webhook для оплаты
- Возврат платежа
- Восстановление складских остатков при refund
- Передача Receipt с фискальными данными
- Включение доставки как позиции в чеке

**Документация:**

- `POST /api/v1/payments` — создать платеж
- `GET /api/v1/payments/{public_id}/status` — получить статус
- `POST /api/v1/payments/{public_id}/refund` — выполнить возврат
- `POST /api/v1/payments/tbank/webhook` — webhook Т-Банка (ответ `OK`)

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
        "payment_url": "https://pay.tbank.ru/...",
        "paid_at": null,
        "refunded_at": null,
        "created_at": "2026-01-15 10:00:00"
    }
}
```

## Влияние на склад

При успешном возврате (`POST /api/v1/payments/{public_id}/refund` или webhook со статусом refunded) `PaymentService` вызывает `ProductStockService::restore()`:

- остатки возвращаются только для товаров с ограниченным складом (`stock IS NOT NULL`)
- количество суммируется по позициям заказа
- услуги с безлимитным складом (`stock: null`) не затрагиваются
- повторный вызов безопасен: если у заказа уже заполнен `stock_released_at`, инкремента нет

Неоплаченный заказ (`payment_failed`, `new`, при необходимости `payment_pending`) можно отменить в Filament («Отменить и вернуть на склад») или командой:

```
php artisan orders:release-unpaid-stock
php artisan orders:release-unpaid-stock --execute
```

Без `--execute` команда только показывает отчёт. По умолчанию берутся **все** подходящие заказы (`payment_failed`, `new`, `cancelled` без флага). `payment_pending` — только с `--include-pending`. Опция `--min-age=N` ограничивает выборку по возрасту в часах. После отмены `POST /payments` по этому заказу возвращает 422.

Ночной прогон (03:00 Europe/Moscow) запускается планировщиком Laravel из контейнера (`schedule:work` в supervisor):

```
orders:release-unpaid-stock --execute --include-pending --min-age=12
```

Списание остатков происходит при оформлении заказа (`POST /api/v1/order`), подробнее — в `docs/features/products.md`.

## Фискализация

Чек уходит в Т-Банк объектом `Receipt` вместе с методом `Init`. Касса и ОФД настраиваются в личном кабинете Т-Банка.

| Переменная | Описание | По умолчанию |
| --- | --- | --- |
| `TBANK_FISCAL_ENABLED` | Передавать чек | `false` |
| `TBANK_FISCAL_TAXATION` | Система налогообложения (`usn_income`) | `usn_income` |
| `TBANK_FISCAL_TAX` | Ставка НДС (`vat5`) | `vat5` |
| `TBANK_FISCAL_PAYMENT_METHOD` | Признак способа расчёта | `full_payment` |
| `TBANK_FISCAL_PAYMENT_OBJECT` | Признак предмета расчёта для товара | `commodity` |
| `TBANK_FISCAL_DELIVERY_PAYMENT_OBJECT` | Признак предмета для доставки | `service` |
| `TBANK_FISCAL_DELIVERY_NAME` | Название позиции доставки | `Доставка` |

- `ReceiptBuilder` собирает позиции, доставку и контакты покупателя
- `TBankGateway` подписывает запрос и отправляет `Init`, `GetState` и `Cancel`
- Сумма позиций чека совпадает с суммой платежа; для каждой позиции `Amount = Price × Quantity`

Уведомление банка: `POST /api/v1/payments/tbank/webhook`. Подпись `Token` проверяется паролем терминала. Успешный ответ — текст `OK`.

Статусы Т-Банка: `CONFIRMED` — оплачен, `REFUNDED` и `PARTIAL_REFUNDED` — возврат, `REJECTED`, `CANCELED`, `AUTH_FAIL`, `DEADLINE_EXPIRED`, `REVERSED` — ошибка.
