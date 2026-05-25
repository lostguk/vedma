# Промокоды

## Описание

Система промокодов позволяет предоставлять процентные скидки на товары определённых категорий. Промокоды имеют срок действия и привязываются к категориям.

## Модель данных

### PromoCode (`promo_codes`)

| Поле | Тип | Описание |
|------|-----|----------|
| `id` | int | Уникальный идентификатор |
| `code` | string | Код промокода (уникальный) |
| `start_date` | date | Дата начала действия |
| `end_date` | date | Дата окончания действия |
| `discount_type` | string | Тип скидки (`percent`) |
| `discount_value` | float | Значение скидки (процент) |
| `created_at` | datetime | Дата создания |
| `updated_at` | datetime | Дата обновления |

> **Примечание:** тип `fixed` (фиксированная скидка) был удалён миграцией `2026_03_26_080808`. Сейчас поддерживается только процентная скидка.

### Связи

- `categories()` — BelongsToMany через pivot-таблицу `promo_code_category`. Определяет, на какие категории действует промокод.

## Бизнес-логика

### Применение промокода (OrderCalculationService)

1. Клиент отправляет `POST /api/v1/order/calculate` с `items` и `promo_code`.
2. `PromoCodeRepository::findActiveByCode()` ищет промокод:
   - Проверяет совпадение кода
   - Проверяет, что текущая дата между `start_date` и `end_date`
3. Если промокод найден и активен — применяется скидка к товарам из привязанных категорий.
4. Если промокод не привязан к категориям — применяется ко всем товарам.

### Статусы ответа

| Статус | Описание |
|--------|----------|
| `applied` | Промокод найден и применён |
| `not_exists` | Промокод не найден или срок действия истёк |
| `not_applied` | Промокод найден, но неприменим к товарам в корзине |

### Расчёт скидки

- Скидка применяется к каждому товару отдельно: `price * (1 - discount_value / 100)`
- Скидка округляется до целых копеек
- Итого: `total_with_discount` = сумма (цена со скидкой * количество)

## API Endpoints

### POST /api/v1/order/calculate

Расчёт стоимости заказа с проверкой промокода.

**Тело запроса:**
```json
{
  "items": [
    { "id": 1, "count": 2 },
    { "id": 5, "count": 1 }
  ],
  "promo_code": "SPRING25"
}
```

**Ответ:**
```json
{
  "status": "success",
  "data": {
    "products": [...],
    "total_without_discount": 3500,
    "total_with_discount": 2625,
    "promo_code_status": "applied"
  }
}
```

## Управление (Filament)

- Ресурс: `PromoCodeResource`
- Раздел: Каталог → Промокоды
- CRUD: создание, редактирование, удаление
- Поля формы: код, даты начала/окончания, тип скидки, значение, привязка к категориям

## Связанные файлы

- `app/Models/PromoCode.php`
- `app/Repositories/PromoCodeRepository.php` — `findActiveByCode(string)`
- `app/Services/OrderCalculationService.php` — расчёт с промокодом
- `app/Filament/Resources/PromoCodeResource.php`
- `database/migrations/2025_06_07_102438_create_promo_codes_table.php`
- `database/migrations/2026_03_26_080808_remove_fixed_discount_type_from_promo_codes.php`
