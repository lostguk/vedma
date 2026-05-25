# Профиль пользователя

## Описание

Просмотр и обновление данных авторизованного пользователя. Доступен только с JWT-токеном (middleware `auth:sanctum`).

## Модель данных

### User (`users`) — релевантные поля

| Поле | Тип | Описание |
|------|-----|----------|
| `first_name` | string | Имя |
| `last_name` | string | Фамилия |
| `middle_name` | string\|null | Отчество |
| `email` | string | Email |
| `phone` | string\|null | Телефон |
| `address` | string\|null | Адрес |
| `is_admin` | bool | Флаг администратора |
| `email_verified_at` | datetime\|null | Дата верификации email |

## API Endpoints

### GET /api/v1/profile

Получение данных текущего пользователя.

**Headers:** `Authorization: Bearer <token>`

**Ответ:**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "first_name": "Анна",
    "last_name": "Иванова",
    "middle_name": "Сергеевна",
    "full_name": "Иванова Анна Сергеевна",
    "email": "anna@mail.ru",
    "phone": "+7 (900) 123-45-67",
    "address": "г. Москва, ул. Ленина, д. 10",
    "email_verified": true
  }
}
```

### PATCH /api/v1/profile

Обновление данных профиля.

**Headers:** `Authorization: Bearer <token>`

**Тело запроса:**
```json
{
  "first_name": "Анна",
  "last_name": "Петрова",
  "middle_name": "Сергеевна",
  "email": "anna.new@mail.ru",
  "phone": "+7 (900) 123-45-67",
  "address": "г. Санкт-Петербург, Невский пр., д. 1"
}
```

## Валидация (UpdateProfileRequest)

| Поле | Правила |
|------|---------|
| `first_name` | required, string, max:255 |
| `last_name` | required, string, max:255 |
| `middle_name` | nullable, string, max:255 |
| `email` | required, email, unique:users (игнорируя текущего пользователя) |
| `phone` | nullable, string |
| `address` | nullable, string |

## Бизнес-логика

- `UserProfileService::getProfile(User)` — возвращает данные пользователя
- `UserProfileService::updateProfile(User, array)` — обновляет только переданные поля
- Bootstrap на фронте: при наличии токена в localStorage → `GET /profile` → восстановление сессии

## Связанные файлы

- `app/Http/Controllers/Api/V1/ProfileController.php`
- `app/Http/Requests/Api/V1/UpdateProfileRequest.php`
- `app/Http/Resources/V1/UserResource.php`
- `app/Services/User/UserProfileService.php`
- `app/Repositories/UserRepository.php`
- `routes/user.php`
