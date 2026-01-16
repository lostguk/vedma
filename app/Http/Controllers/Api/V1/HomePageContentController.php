<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\V1\HomePageContentResource;
use App\Services\HomePageContentService;
use Illuminate\Http\JsonResponse;

/**
 * @group Главная страница
 *
 * API для получения контента главной страницы
 */
final class HomePageContentController extends ApiController
{
    public function __construct(
        private readonly HomePageContentService $service,
    ) {}

    /**
     * Получить данные главной страницы
     *
     * Возвращает контент главной страницы, включая:
     * - Первый экран (hero) с заголовком, подзаголовком, кнопкой и изображением
     * - Блок "Наша магия — ваша сила" (about) с описанием, статистикой и изображениями
     * - Выбранные категории товаров (без дочерних категорий) с товарами внутри каждой категории
     * - Внутри каждой категории отображается до 3 товаров, которые привязаны к этой категории и всем её дочерним категориям (рекурсивно)
     *
     * @response 200 scenario="Успешный запрос" {
     *   "data": {
     *     "hero": {
     *       "title": "МАГИЯ ЖИВЕТ В КАЖДОМ ИЗ НАС",
     *       "subtitle": "Вопрос в том, готовы ли вы её пробудить?",
     *       "button": {
     *         "label": "Каталог",
     *         "url": "/catalog"
     *       },
     *       "image": "http://localhost:8000/storage/home/hero_image.png",
     *       "features": [
     *         { "text": "🔮Авторские изделия заряженные энергией" },
     *         { "text": "🌙Традиционные рецепты и обряды" },
     *         { "text": "🕯️Ручная работа и натуральные материалы" }
     *       ]
     *     },
     *     "about": {
     *       "title": "🔮НАША МАГИЯ – ВАША СИЛА",
     *       "description": "Мы верим в силу природы...",
     *       "trust": {
     *         "title": "🌙Почему нам доверяют?",
     *         "items": [
     *           { "title": "Проверенные рецепты", "image": "..." },
     *           { "title": "Только натуральные материалы", "image": "..." },
     *           { "title": "Энергетическая зарядка каждого изделия", "image": "..." }
     *         ]
     *       },
     *       "motto": "✨Магия в ваших руках – главное, использовать ее с осознанием.",
     *       "images": {
     *         "left": "http://localhost:8000/storage/home/about_left_image.png",
     *         "right": "http://localhost:8000/storage/home/about_right_image.png"
     *       },
     *       "stats": {
     *         "title": "🧮Мы в цифрах",
     *         "items": [
     *           { "value": "3600+", "label": "Довольных клиентов", "text": "..." },
     *           { "value": "6", "label": "Лет", "text": "..." },
     *           { "value": "500+", "label": "Моделей свечей", "text": "..." }
     *         ]
     *       },
     *       "moreButton": {
     *         "label": "Подробнее о нас",
     *         "url": "/about"
     *       }
     *     },
     *     "categories": [
     *       {
     *         "id": 1,
     *         "name": "Ритуальные Свечи",
     *         "slug": "ritualnye-svechi",
     *         "description": "Свечи для различных ритуалов",
     *         "icon": "http://localhost:8000/storage/1/icon.svg",
     *         "parent_id": null,
     *         "sort_order": 1,
     *         "is_visible": true,
     *         "products": [
     *           {
     *             "id": 1,
     *             "name": "Ароматическая свеча Лаванда",
     *             "slug": "aromaticheskaya-svecha-lavanda",
     *             "description": "Успокаивающий аромат лаванды",
     *             "price": 1200.99,
     *             "old_price": 1500.00,
     *             "is_new": true,
     *             "is_bestseller": false,
     *             "dimensions": { "weight": 350, "width": 10, "height": 12, "length": 10 },
     *             "categories": [],
     *             "related": [],
     *             "images_urls": ["http://localhost:8000/storage/1/image.jpg"],
     *             "image_url": "http://localhost:8000/storage/1/image.jpg",
     *             "preview_url": "http://localhost:8000/storage/1/preview.jpg",
     *             "thumb_url": "http://localhost:8000/storage/1/preview.jpg",
     *             "thumb_small_url": "http://localhost:8000/storage/1/thumb.jpg",
     *             "created_at": "2025-01-01T00:00:00.000000Z",
     *             "updated_at": "2025-01-01T00:00:00.000000Z"
     *           }
     *         ]
     *       }
     *     ]
     *   }
     * }
     */
    public function show(): JsonResponse
    {
        $content = $this->service->getHomePageContent();

        return $this->successResponse(new HomePageContentResource($content));
    }
}
