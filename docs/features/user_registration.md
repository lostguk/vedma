# Регистрация пользователя

## Описание

Позволяет новым пользователям создавать учётную запись для доступа к личному кабинету, истории заказов и чату поддержки. После регистрации требуется подтверждение email.

## API Endpoint

- `POST /api/v1/register`

## Модель данных

- **User** (`users`)
  - `first_name` — имя (обязательное)
  - `last_name` — фамилия (обязательное)
  - `middle_name` — отчество (необязательное)
  - `email` — email (уникальный, обязательный)
  - `password` — пароль (min 8, confirmed)
  - `phone` — телефон (формат: `+7 (XXX) XXX-XX-XX`)
  - `address` — адрес (необязательный)

## Права доступа

- Регистрация доступна только неавторизованным пользователям.

## Бизнес-правила

1. Email должен быть уникальным в таблице `users`.
2. Пароль минимум 8 символов, требуется подтверждение (`password_confirmation`).
3. После регистрации отправляется письмо верификации (`VerifyEmailNotification`).
4. Пользователь **не получает токен** при регистрации — требуется подтверждение email.
5. После подтверждения email (GET `/api/v1/verify-registration/{user}/{hash}`) пользователь может войти через `POST /api/v1/login`.
6. Повторная отправка письма верификации: `POST /api/v1/verify-registration/resend`.

## Валидация (RegisterRequest)

| Поле | Правила |
|------|---------|
| `first_name` | required, string, max:255 |
| `last_name` | required, string, max:255 |
| `middle_name` | nullable, string, max:255 |
| `email` | required, email, unique:users |
| `password` | required, string, min:8, confirmed |
| `phone` | nullable, string, regex телефона |
| `address` | nullable, string |

## Flow

```
1. POST /register → RegistrationService::register()
2. Создание User + отправка VerifyEmailNotification
3. Пользователь получает email со ссылкой верификации
4. GET /verify-registration/{user}/{hash} (signed URL) → верификация email
5. POST /login → получение JWT токена
```

## Связанные файлы

- `app/Http/Controllers/Api/V1/Auth/RegisterController.php`
- `app/Http/Controllers/Api/V1/Auth/VerifyRegistrationController.php`
- `app/Http/Controllers/Api/V1/Auth/ResendVerificationController.php`
- `app/Http/Requests/Api/V1/Auth/RegisterRequest.php`
- `app/Services/Auth/RegistrationService.php`
- `app/Notifications/VerifyEmailNotification.php`
- `routes/auth.php`

## Примечание

> Для корректной работы с правами и git в Docker настройте `HOST_UID` и `HOST_GID` в `.env` (см. README.md).
