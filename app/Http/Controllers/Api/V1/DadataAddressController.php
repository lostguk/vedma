<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\DadataAddressSuggestRequest;
use App\Services\DaData\AddressSuggestService;
use Illuminate\Http\JsonResponse;
use RuntimeException;

final class DadataAddressController extends ApiController
{
    /**
     * @group Оформление заказа
     *
     * Подсказки адреса через DaData.
     *
     * @response 200 {"status":"success","message":"Success","data":{"suggestions":[{"value":"г Москва, ул Хабаровская"}]}}
     */
    public function suggest(
        DadataAddressSuggestRequest $request,
        AddressSuggestService $addressSuggestService
    ): JsonResponse {
        try {
            $result = $addressSuggestService->suggest(
                $request->input('query'),
                $request->input('count'),
                $request->input('language'),
                $request->input('division')
            );
        } catch (RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }

        return $this->successResponse($result);
    }
}
