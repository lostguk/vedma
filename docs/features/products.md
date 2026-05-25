# Товары

## Описание

Каталог товаров магазина с поддержкой фильтрации, сортировки, пагинации, управления складом (stock), множественных изображений и связанных товаров.

## Модель данных

### Product (`products`)

| Поле            | Тип          | Описание                                        |
| --------------- | ------------ | ----------------------------------------------- |
| `id`            | int          | Уникальный идентификатор                        |
| `name`          | string       | Название товара                                 |
| `slug`          | string       | URL-slug (автогенерация через Spatie Sluggable) |
| `description`   | string\|null | Описание (HTML)                                 |
| `price`         | float        | Цена                                            |
| `old_price`     | float\|null  | Старая цена (для отображения скидки)            |
| `weight`        | float        | Вес в граммах                                   |
| `width`         | float\|null  | Ширина в см                                     |
| `height`        | float\|null  | Высота в см                                     |
| `length`        | float\|null  | Длина в см                                      |
| `is_new`        | bool         | Флаг «Новинка»                                  |
| `is_bestseller` | bool         | Флаг «Хит продаж»                               |
| `stock`         | int\|null    | Количество на складе (`null` = неограниченно)   |

### Связи

- `categories()` — BelongsToMany Category (через `product_category`)
- `related()` — BelongsToMany self (через `product_related`) — рекомендуемые товары
- `relatedToProducts()` — обратная связь BelongsToMany

### Медиа (Spatie Media Library)

- Коллекция `images` — множественные изображения (jpeg, png, webp), диск `public`
- Конверсия `thumb` — 100×100px (sharpen)
- Конверсия `preview` — 900×600px (Fit::Contain, quality 90)
- Responsive images включены

### Route Key

- `getRouteKeyName()` → `slug` — все API-роуты по slug, не по id

## Бизнес-логика

### Наличие (Stock)

- `stock: null` — неограниченное количество (услуги, гадания, консультации)
- `stock: 0` — нет в наличии
- `stock: N` — N единиц на складе
- `isInStock()` — `true` если `stock === null || stock > 0`
- Scope `inStock()` — фильтрация товаров с наличием
- При создании заказа: `stock` уменьшается на количество единиц
- При возврате (refund): `stock` увеличивается обратно

### Scopes

| Scope             | Описание                     |
| ----------------- | ---------------------------- |
| `new()`           | `is_new = true`              |
| `bestseller()`    | `is_bestseller = true`       |
| `search($search)` | `name LIKE %search%`         |
| `inStock()`       | `stock IS NULL OR stock > 0` |

### Фильтрация (ProductFilterService)

Применяет фильтры из запроса к query builder:

- `category` — slug категории (включая дочерние)
- `search` — поиск по названию
- `price_from` / `price_to` — диапазон цен
- `is_new` — только новинки
- `is_bestseller` — только хиты продаж
- `ids` — конкретные ID товаров (через запятую)
- `sort` — сортировка: `price_asc`, `price_desc`, `name_asc`, `created_at_desc` (по умолчанию)

## API Endpoints

### GET /api/v1/products

Список товаров с фильтрацией и пагинацией.

**Query параметры:**

```
page=1&per_page=9&sort=price_asc&category=svechi&search=ритуальная&price_from=100&price_to=5000&is_new=1&is_bestseller=1&ids=1,2,3
```

**Ответ (стандартная Laravel Resource collection):**

```json
{
  "data": [
    { "id": 1, "name": "Свеча ритуальная", "slug": "svecha-ritualnaya", "price": 450, "stock": 25, "in_stock": true, ... }
  ],
  "links": { ... },
  "meta": { "total": 42, "last_page": 5, "current_page": 1, "per_page": 9 }
}
```

> **Важно:** ProductController@index использует стандартный Laravel Resource collection (data + links + meta), а не обёрнутый в envelope `successPaginatedResponse`.

### GET /api/v1/products/{slug}

Детали товара по slug.

**Ответ:** `{ "data": { ... полный объект товара с categories, related, breadcrumbs, images_urls ... } }`

## Управление (Filament)

- Ресурс: `ProductResource`
- RelationManagers: `CategoriesRelationManager`, `RelatedRelationManager`
- Поля: название, slug, описание, цена, старая цена, вес, размеры, флаги, сортировка, склад, изображения
- Раздел: Каталог → Товары

## Связанные файлы

- `app/Models/Product.php`
- `app/Http/Controllers/Api/V1/ProductController.php`
- `app/Http/Requests/Api/V1/ProductIndexRequest.php`
- `app/Http/Resources/V1/ProductResource.php`
- `app/Services/ProductFilterService.php`
- `app/Repositories/ProductRepository.php`
- `app/Filament/Resources/ProductResource.php`
- `database/migrations/2025_03_26_070727_create_products_table.php`
- `database/migrations/2026_04_03_120000_add_stock_to_products_table.php`
