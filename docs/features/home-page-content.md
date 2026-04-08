# Главная страница

## Описание

Контент главной страницы магазина: слайдер (HeroSlides), блоки «О нас», статистика, кнопки и категории товаров с превью. Управляется целиком через Filament-админку (единственная запись, без возможности создания/удаления).

## Модели данных

### HomePageContent (`home_page_contents`)

Одна запись (singleton) с полями для всех секций главной страницы:

**Hero-секция:**
- `hero_title`, `hero_subtitle`, `hero_button_label`, `hero_button_url`, `hero_image_path`
- `hero_feature_1_text`, `hero_feature_1_image_path` (×3 features)

**About-секция:**
- `about_title`, `about_description`, `about_motto`
- `about_trust_title`, `about_trust_feature_1_title`, `about_trust_feature_1_image_path` (×3 trust features)
- `about_left_image_path`, `about_right_image_path`
- `about_more_button_label`, `about_more_button_url`

**Stats-секция:**
- `stats_title`
- `stats_item_1_value`, `stats_item_1_label`, `stats_item_1_text` (×3 stats)

### HeroSlide (`hero_slides`)

Отдельные слайды для слайдера (см. [hero-slides.md](hero-slides.md)).

### Связи

- `categories()` — BelongsToMany Category через `category_home_page_content` (pivot: `sort_order`)
- Категории определяют, какие товары показываются на главной странице

## API Endpoint

### GET /api/v1/home

Контент главной страницы с категориями и товарами.

**Ответ:**
```json
{
  "status": "success",
  "data": {
    "slides": [
      { "id": 1, "title": "Ритуальные свечи", "accent": "ручной работы", "subtitle": "...", "button_text": "Смотреть", "button_url": "/catalog", "image_url": "..." }
    ],
    "categories": [
      {
        "id": 1,
        "name": "Свечи",
        "slug": "svechi",
        "products": [
          { "id": 1, "name": "Свеча ритуальная", "price": 450, ... }
        ]
      }
    ]
  }
}
```

## Бизнес-логика

- `HomePageContentService::getHomePageContent()` загружает единственную запись HomePageContent
- `getProductsForCategory(Category)` — загружает товары категории и всех её потомков
- Слайды загружаются из `HeroSlide` (scope `active`, `ordered`)
- Формат ответа: `HomePageContentResource` + вложенные `HeroSlideResource` + `HomePageCategoryResource`

## Управление (Filament)

- Ресурс: `HomePageContentResource`
- **Нельзя создать/удалить** — только редактирование существующей записи
- Табы:
  - **Слайдер** — ссылка на управление слайдами (HeroSlideResource)
  - **Товары** — Repeater для выбора категорий с drag&drop сортировкой

## Связанные файлы

- `app/Models/HomePageContent.php`
- `app/Models/HeroSlide.php`
- `app/Http/Controllers/Api/V1/HomePageContentController.php`
- `app/Http/Resources/V1/HomePageContentResource.php`
- `app/Http/Resources/V1/HomePageCategoryResource.php`
- `app/Http/Resources/V1/HeroSlideResource.php`
- `app/Services/HomePageContentService.php`
- `app/Repositories/HomePageContentRepository.php`
- `app/Filament/Resources/HomePageContentResource.php`
- `database/migrations/2025_11_13_092847_create_home_page_contents_table.php`
- `database/migrations/2025_12_16_132629_create_category_home_page_content_table.php`
