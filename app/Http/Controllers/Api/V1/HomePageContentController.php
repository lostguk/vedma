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
    ) {
    }

    /**
     * Получить данные главной страницы
     *
     * Возвращает два блока: первый экран и блок "Наша магия — ваша сила".
     *
     * @response 200 {
     *   "data": {
     *     "hero": { "title": "МАГИЯ ЖИВЕТ В КАЖДОМ ИЗ НАС", "...": "..." },
     *     "about": { "title": "НАША МАГИЯ – ВАША СИЛА", "...": "..." }
     *   }
     * }
     */
    public function show(): JsonResponse
    {
        $content = $this->service->getHomePageContent();

        return $this->successResponse(new HomePageContentResource($content));
    }
}
