# Формат ошибок API

## Описание

Стандартные форматы ответов при ошибках API бэкенда. Фронтенд использует функцию `getApiErrors()` для парсинга.

## Базовый контроллер (ApiController)

Все API-контроллеры наследуют `ApiController`, который предоставляет методы:

```php
$this->successResponse($data, $message, $statusCode)      // 200
$this->errorResponse($message, $statusCode)                // 4xx/5xx
$this->noContentResponse()                                 // 204
$this->successPaginatedResponse($data, $message)           // 200 с пагинацией
```

## Форматы ответов

### Успешный ответ (200)

```json
{
  "status": "success",
  "message": "OK",
  "data": { ... }
}
```

### Успешный с пагинацией (200)

```json
{
  "status": "success",
  "message": "OK",
  "data": {
    "data": [ ... ],
    "meta": {
      "total": 42,
      "per_page": 15,
      "current_page": 1,
      "last_page": 3
    }
  }
}
```

> **Исключение:** `ProductController@index` и `CategoryController@index/show` используют стандартный Laravel Resource collection (`data` + `links` + `meta`) без обёртки в envelope.

### Ошибка валидации (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "Поле email обязательно для заполнения.",
      "Такой email уже зарегистрирован."
    ],
    "password": [
      "Пароль должен быть не менее 8 символов."
    ]
  }
}
```

### Неавторизован (401)

```json
{
  "message": "Unauthenticated."
}
```

### Ошибка бизнес-логики (400/403/404/500)

```json
{
  "status": "error",
  "message": "Описание ошибки"
}
```

### Не найдено (404)

```json
{
  "message": "No query results for model [App\\Models\\Product]."
}
```

Или кастомный:
```json
{
  "status": "error",
  "message": "Товар не найден"
}
```

## Парсинг ошибок на фронте

Стандартная функция `getApiErrors()`:

```javascript
function getApiErrors(error) {
  if (error.response?.data?.errors) {
    return Object.values(error.response.data.errors).flat().join('. ')
  }
  return error.response?.data?.message || 'Произошла ошибка'
}
```

### Приоритет парсинга:
1. `response.data.errors` — объект с массивами ошибок валидации (422) → объединяются в строку
2. `response.data.message` — текстовое сообщение ошибки
3. Fallback: `'Произошла ошибка'`

## HTTP-коды ответов

| Код | Когда |
|-----|-------|
| 200 | Успешный запрос |
| 201 | Ресурс создан (заказ, тема, сообщение) |
| 204 | Успешное действие без контента (logout) |
| 400 | Некорректный запрос |
| 401 | Не авторизован / токен протух |
| 403 | Доступ запрещён (не верифицирован email, чужая тема) |
| 404 | Ресурс не найден |
| 422 | Ошибка валидации |
| 500 | Серверная ошибка |

## Обработка 401 на фронте

Response interceptor в `api/client.js`:
- При получении 401 → автоматически удаляет токен из localStorage
- AuthContext при следующем рендере обнаруживает отсутствие токена → `user = null`
