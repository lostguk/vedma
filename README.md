<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Магазин магических товаров

## О проекте

Магазин магических товаров - это современный веб-сервис для продажи магических товаров и аксессуаров.
Проект построен на Laravel 11 с использованием современных технологий и практик.

## Технический стек

-   PHP 8.3
-   Laravel 11
-   Docker
-   MySQL 8.0
-   Filament (админ-панель)
-   Scribe (документация API)

## Установка и запуск

### Требования

-   Docker и Docker Compose
-   Git

### Шаги по установке

1. Клонируйте репозиторий:

    ```
    git clone git@github.com:username/shop.git
    cd shop
    ```

2. Скопируйте файл окружения:

    ```
    cp .env.example .env
    ```

3. Запустите Docker-контейнеры:

    ```
    docker-compose up -d
    ```

4. Веб-приложение будет доступно по адресу:
    ```
    http://localhost:8000
    ```

### API Документация

Документация API автоматически генерируется с помощью Scribe и доступна по адресу:

```
http://localhost:8000/docs
```

API документация организована в следующие группы для удобной навигации:

-   **Аутентификация**: регистрация и авторизация пользователей
-   **Категории**: просмотр категорий товаров
-   **Продукты**: работа с товарами, фильтрация и сортировка

Документация включает примеры запросов, подробные описания параметров и ответов, а также позволяет тестировать API прямо из браузера через функцию "Try It Out".

Для обновления документации после внесения изменений в API выполните:

```
docker exec shop_php php artisan scribe:generate
```

### Группировка эндпоинтов в API

При добавлении новых эндпоинтов в API, вы можете включать их в существующие группы или создавать новые с помощью аннотации `@group`.

Например:

```php
/**
 * @group Заказы
 * API для работы с заказами
 */
class OrderController extends ApiController
{
    // методы контроллера
}
```

Порядок групп можно настроить в файле `config/scribe.php` в секции `groups.order`.

### Админ-панель

Админ-панель построена на основе Filament и доступна по адресу:

```
http://localhost:8000/admin
```

## Разработка

### Генерация документации API

При запуске контейнеров документация API генерируется автоматически. Если вы внесли изменения в API и хотите обновить документацию, выполните:

```
docker exec shop_php php artisan scribe:generate
```

### Запуск тестов

```
docker exec shop_php php artisan test
```
