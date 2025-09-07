# Расчёт доставки (Metaship)

## Описание

Эндпоинт рассчитывает стоимость и варианты доставки через Metaship на основе списка товаров и адреса доставки.

## API Endpoint

-   POST `/api/v1/shipping/calculate`

### Параметры

-   `products` (array, required): список товаров
    -   `id` (integer, required): ID товара
    -   `quantity` (integer, required, >=1): количество
-   `address` (string, required): адрес доставки (город, улица, дом)

### Пример запроса

```json
{
    "products": [
        { "id": 1, "quantity": 2 },
        { "id": 5, "quantity": 1 }
    ],
    "address": "Москва, ул. Пушкина, д. 1"
}
```

### Пример ответа

```json
{
    "status": "success",
    "message": "Success",
    "data": {
        "price": 350,
        "options": [{ "carrier": "CDEK", "service": "Курьер", "price": 350 }]
    }
}
```

## Тестирование

-   Тесты расположены в `tests/Feature/Api/V1/ShippingCalculationTest.php`.
-   Покрывают валидацию и успешный сценарий (с моками HTTP).

## Примечания

-   Интеграция с Metaship выполнена через Laravel HTTP Client.
-   Конфигурация: `config/services.php` → `metaship`.
-   Переменные окружения: `METASHIP_BASE_URL`, `METASHIP_API_KEY`, `METASHIP_API_SECRET`.
