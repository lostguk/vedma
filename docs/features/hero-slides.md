# Hero-слайдер

## Описание

Слайдер на главной странице магазина. Каждый слайд содержит заголовок, акцент, подзаголовок, кнопку и фоновое изображение. Управляется через Filament-админку.

## Модель данных

### HeroSlide (`hero_slides`)

| Поле          | Тип          | Описание                                               |
| ------------- | ------------ | ------------------------------------------------------ |
| `id`          | int          | Уникальный идентификатор                               |
| `title`       | string       | Основной заголовок                                     |
| `accent`      | string\|null | Акцентная часть (выделяется другим стилем)             |
| `subtitle`    | string\|null | Подзаголовок                                           |
| `button_text` | string\|null | Текст кнопки                                           |
| `button_url`  | string\|null | Ссылка кнопки                                          |
| `image_path`  | string\|null | Путь к фоновому изображению (рекомендация: 1920×900px) |
| `is_active`   | bool         | Активен ли слайд                                       |

### Scopes

- `active()` — `is_active = true`
- `ordered()` — `ORDER BY id`

## API

Слайды отдаются как часть ответа `GET /api/v1/home`:

```json
{
    "data": {
        "slides": [
            {
                "id": 1,
                "title": "Ритуальные свечи",
                "accent": "ручной работы",
                "subtitle": "Каждая свеча создаётся с намерением...",
                "button_text": "Смотреть свечи",
                "button_url": "/catalog",
                "image_url": "http://localhost:8000/storage/hero-slides/slide1.jpg"
            }
        ]
    }
}
```

Отдельного API-эндпоинта для слайдов нет — они загружаются через `HomePageContentService`.

## Управление (Filament)

- Ресурс: `HeroSlideResource`
- Раздел: Контент → Слайдер на главной (навигация скрыта, доступ через HomePageContentResource)
- Функции:
    - Создание/редактирование/удаление слайдов
    - Загрузка изображений с редактором (imageEditor)
    - Включение/выключение (toggle `is_active`)
    - Bulk-удаление

## Связанные файлы

- `app/Models/HeroSlide.php`
- `app/Http/Resources/V1/HeroSlideResource.php`
- `app/Filament/Resources/HeroSlideResource.php`
- `database/migrations/2026_04_03_100000_create_hero_slides_table.php`
