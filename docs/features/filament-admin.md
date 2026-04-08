# Filament-админка

## Описание

Административная панель магазина построена на Filament 3. Доступна по адресу `/admin`. Вход только для пользователей с `is_admin = true`.

## Доступ

- URL: `http://localhost:8000/admin` (Dev) / `http://localhost:8080/admin` (Production)
- Авторизация: стандартная Filament-авторизация через модель User
- Доступ: только пользователи с флагом `is_admin = true`

## Ресурсы (Resources)

### Каталог

| Ресурс | Модель | Описание | RelationManagers |
|--------|--------|----------|------------------|
| `CategoryResource` | Category | Категории товаров | `ChildrenRelationManager` |
| `ProductResource` | Product | Товары | `CategoriesRelationManager`, `RelatedRelationManager` |
| `PromoCodeResource` | PromoCode | Промокоды | — |
| `OrderResource` | Order | Заказы | `ItemsRelationManager`, `PaymentsRelationManager` |

### Контент

| Ресурс | Модель | Описание | Особенности |
|--------|--------|----------|-------------|
| `HomePageContentResource` | HomePageContent | Главная страница | Singleton (нельзя создать/удалить) |
| `HeroSlideResource` | HeroSlide | Слайдер | Drag&drop, скрыт из навигации |
| `PageResource` | Page | Статические страницы | Rich Editor для контента |

### Пользователи

| Ресурс | Модель | Описание | Особенности |
|--------|--------|----------|-------------|
| `UserResource` | User | Пользователи | Управление данными, флаг is_admin |
| `TopicResource` | Topic | Обращения поддержки | `MessagesRelationManager` |

## Управление заказами

### Форма заказа (секции)

1. **Пользователь** — привязка к User (disabled)
2. **Данные пользователя** — ФИО, email, телефон
3. **Адрес доставки** — адрес
4. **Доставка** — тип (Cdek/PostOffice), статус (pending/shipped/delivered)
5. **Детали заказа** — промокод, суммы, статус оплаты, тип оплаты, дата оплаты, комментарий
6. **Оплата** — информация о последнем платеже (статус, ID, ссылка, дата)

### Статусы заказов

| Статус | Название | Цвет |
|--------|----------|------|
| `new` | Новый | gray |
| `payment_pending` | Ожидает оплату | warning |
| `payment_failed` | Ошибка оплаты | danger |
| `paid` | Оплачен | success |
| `refunded` | Возврат | info |
| `cancelled` | Отменён | danger |

### Статусы доставки

| Статус | Название |
|--------|----------|
| `pending` | Ожидает |
| `shipped` | Отправлен |
| `delivered` | Доставлен |

## Управление категориями

- Иерархическая структура (parent → children через RelationManager)
- Загрузка иконок (Spatie Media Library)
- Настройка видимости и исключения из доставки
- SEO-поля (meta_title, meta_description)

## Управление главной страницей

- Singleton-запись (одна на всё приложение)
- Табы: Слайдер | Товары
- Слайдер → ссылка на `HeroSlideResource`
- Товары → Repeater для выбора категорий с drag&drop

## Управление обращениями (Topics)

- Список тем обращений пользователей
- `MessagesRelationManager` — просмотр/ответ на сообщения
- Вложения пользователей (jpeg, png, webp, pdf)

## Экспорт данных

- Экспорт заказов в Excel (OrderExcelExporter)
- Экспорт складских остатков (StockExcelExporter)

## Очистка кеша Filament

```bash
# DEV-окружение
./dev.sh dev-filament-cache

# Sail
./dev.sh artisan filament:clear-cache
```

## Связанные файлы

Все ресурсы расположены в `app/Filament/Resources/`:
- `CategoryResource.php` + Pages + `ChildrenRelationManager`
- `ProductResource.php` + Pages + `CategoriesRelationManager`, `RelatedRelationManager`
- `PromoCodeResource.php` + Pages
- `OrderResource.php` + Pages + `ItemsRelationManager`, `PaymentsRelationManager`
- `HomePageContentResource.php` + Pages
- `HeroSlideResource.php` + Pages
- `PageResource.php` + Pages
- `UserResource.php` + Pages
- `TopicResource.php` + Pages + `MessagesRelationManager`
