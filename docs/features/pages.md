# Статические страницы

## Описание

Система управляемых через админку статических страниц (доставка, возврат, контакты и др.). Контент хранится в БД в формате HTML, управляется через Filament.

## Модель данных

### Page (`pages`)

| Поле | Тип | Описание |
|------|-----|----------|
| `id` | int | Уникальный идентификатор |
| `title` | string | Заголовок страницы |
| `slug` | string | URL-slug (автогенерация через Spatie Sluggable) |
| `description` | string\|null | Краткое описание |
| `text` | text | Содержимое страницы (HTML) |
| `is_visible_in_header` | int | Показывать в хедере сайта |
| `is_visible_in_footer` | int | Показывать в футере сайта |

### Связи

Нет связей с другими моделями.

## API Endpoints

### GET /api/v1/pages

Список всех страниц.

**Ответ:**
```json
{
  "status": "success",
  "data": [
    { "id": 3, "title": "Доставка и оплата", "slug": "dostavka-i-oplata", "is_visible_in_header": 1, "is_visible_in_footer": 1 }
  ]
}
```

### GET /api/v1/pages/{id}

Содержимое конкретной страницы.

**Ответ:**
```json
{
  "status": "success",
  "data": {
    "id": 3,
    "title": "Доставка и оплата",
    "slug": "dostavka-i-oplata",
    "description": null,
    "text": "<h2>Способы доставки</h2><p>Почта России...</p>",
    "is_visible_in_header": 1,
    "is_visible_in_footer": 1
  }
}
```

## Использование на фронте

| Путь фронта | ID страницы | Описание |
|-------------|-------------|----------|
| `/delivery` | 3 | Доставка и оплата |
| `/returns` | 4 | Обмен и возврат |
| `/contacts` | 5 | Контакты |
| `/privacy` | — | Статичный HTML (не из API) |
| `/offer` | — | Статичный HTML (не из API) |

> Страницы `/privacy` и `/offer` рендерятся фронтендом из статичного HTML без запроса к API.

## Управление (Filament)

- Ресурс: `PageResource`
- CRUD: создание, редактирование, удаление
- Поля: заголовок, slug, описание, содержимое (Rich Editor), видимость в хедере/футере
- Раздел: Контент → Страницы

## Связанные файлы

- `app/Models/Page.php`
- `app/Http/Controllers/Api/V1/PageController.php`
- `app/Http/Resources/Api/V1/PageResource.php`
- `app/Services/PageService.php`
- `app/Repositories/PageRepository.php`
- `app/Filament/Resources/PageResource.php`
- `database/migrations/2025_06_08_075448_create_pages_table.php`
- `database/migrations/2025_12_16_071632_add_slug_to_pages_table.php`
