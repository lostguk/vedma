# Категории

## Описание

Иерархическая система категорий для организации товаров магазина. Поддерживает неограниченную вложенность (parent → children → descendants), иконки через Spatie Media Library и флаг исключения из расчёта доставки.

## Модель данных

### Category (`categories`)

| Поле | Тип | Описание |
|------|-----|----------|
| `id` | int | Уникальный идентификатор |
| `name` | string | Название категории |
| `slug` | string | URL-slug |
| `description` | string\|null | Описание |
| `parent_id` | int\|null | ID родительской категории (null = корневая) |
| `sort_order` | int | Порядок сортировки |
| `is_visible` | bool | Видимость категории |
| `exclude_from_shipping` | bool | Исключить из расчёта доставки |
| `meta_title` | string\|null | SEO-заголовок |
| `meta_description` | string\|null | SEO-описание |

### Связи

- `parent()` — BelongsTo Category (родительская)
- `children()` — HasMany Category (дочерние, сортировка по `sort_order`)
- `descendants()` — рекурсивная HasMany через `children()->with('descendants')`
- `homePageContents()` — BelongsToMany HomePageContent (через `category_home_page_content`, pivot: `sort_order`)

### Медиа (Spatie Media Library)

- Коллекция `icon` — одно изображение на категорию (singleFile, диск `public`)
- Конверсия `thumb` — 100×100px

## Бизнес-логика

### Иерархия

- Корневые категории: `parent_id = null`
- Scope `root()` — только корневые, сортировка по `sort_order`
- Scope `visible()` — только с `is_visible = true`
- `getFullPathAttribute()` — полный путь slug'ов (напр. `sveci/ritualnye`)
- `getAllDescendants()` — рекурсивная коллекция всех потомков

### Исключение из доставки

- Метод `isExcludedFromShipping()` проверяет:
  1. Флаг `exclude_from_shipping` на самой категории
  2. Рекурсивно на всех родителях (до 10 уровней)
- Используется в `ShippingCalculationService` для фильтрации товаров при расчёте стоимости доставки
- Пример: категория «Услуги» (гадания, консультации) → товары из неё не участвуют в расчёте

## API Endpoints

### GET /api/v1/categories

Дерево всех видимых категорий с дочерними.

**Ответ:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Свечи",
      "slug": "svechi",
      "icon_url": "http://...",
      "exclude_from_shipping": false,
      "children": [
        { "id": 2, "name": "Ритуальные", "slug": "ritualnye", "children": [] }
      ]
    }
  ]
}
```

### GET /api/v1/categories/{slug}

Одна категория по slug с дочерними.

## Управление (Filament)

- Ресурс: `CategoryResource`
- RelationManager: `ChildrenRelationManager` — управление дочерними категориями
- Поля: название, slug, описание, родитель, порядок, видимость, исключение из доставки, иконка, SEO
- Раздел: Каталог → Категории

## Связанные файлы

- `app/Models/Category.php`
- `app/Http/Controllers/Api/V1/CategoryController.php`
- `app/Http/Resources/V1/CategoryResource.php`
- `app/Filament/Resources/CategoryResource.php`
- `app/Services/Shipping/ShippingCalculationService.php`
- `database/migrations/2025_03_25_131357_create_categories_table.php`
- `database/migrations/2026_04_03_110000_add_exclude_from_shipping_to_categories_table.php`
