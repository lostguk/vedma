<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\V1\PageResource;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;

final class PageController extends ApiController
{
    public function __construct(private readonly PageService $service) {}

    /**
     * @group Страницы
     *
     * Получить список всех страниц
     *
     * Возвращает массив всех страниц.
     *
     * @responseField id int ID страницы
     * @responseField title string Заголовок
     * @responseField description string Описание
     * @responseField text string Текст
     * @responseField is_visible_in_header boolean Показывать в шапке
     * @responseField is_visible_in_footer boolean Показывать в футере
     */
    public function index(): JsonResponse
    {
        $pages = $this->service->getAll();

        return $this->successResponse(PageResource::collection($pages));
    }

    /**
     * @group Страницы
     *
     * Получить страницу по ID
     *
     * @urlParam id int required ID страницы. Пример: 1
     *
     * @responseField id int ID страницы
     * @responseField title string Заголовок
     * @responseField description string Описание
     * @responseField text string Текст
     * @responseField is_visible_in_header boolean Показывать в шапке
     * @responseField is_visible_in_footer boolean Показывать в футере
     */
    public function show(int $id): JsonResponse
    {
        $page = $this->service->getById($id);

        return $this->successResponse(new PageResource($page));
    }
}
