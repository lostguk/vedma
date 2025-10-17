<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\ShippingCalculateRequest;
use App\Services\Shipping\ShippingCalculationService;
use Illuminate\Http\JsonResponse;

final class ShippingController extends ApiController
{
    /**
     * Расчёт стоимости доставки (Metaship) 4444444
     *
     * @group Доставка
     *
     * Позволяет рассчитать стоимость доставки на основе списка товаров и адреса доставки.
     *
     * @response 200 {"status":"success","message":"Success","data":{"price":350,"options":[{"carrier":"CDEK","service":"Курьер","price":350}]}}
     */
    public function calculate(ShippingCalculateRequest $request): JsonResponse
    {
        $service = app(ShippingCalculationService::class);
        $result = $service->calculate(
            $request->validated('products'),
            $request->validated('address')
        );

        return $this->successResponse($result);
    }
}
