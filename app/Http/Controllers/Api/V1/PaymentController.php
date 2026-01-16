<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\PaymentRefundRequest;
use App\Http\Requests\Api\V1\PaymentStoreRequest;
use App\Http\Requests\Api\V1\PaymentWebhookRequest;
use App\Http\Resources\Api\V1\PaymentResource;
use App\Repositories\PaymentRepository;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use Throwable;

final class PaymentController extends ApiController
{
    /**
     * @group Оплата заказа
     *
     * Создать платеж и получить ссылку на оплату.
     *
     * @response 200 scenario="Успешно" {"status":"success","message":"Платеж создан","data":{"id":"uuid","payment_url":"https://..."}}
     */
    public function store(PaymentStoreRequest $request, PaymentService $paymentService): JsonResponse
    {
        try {
            $payment = $paymentService->createPayment(
                (int) $request->input('order_id'),
                $request->input('success_url'),
                $request->input('fail_url'),
            );
        } catch (RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage(), 422);
        } catch (Throwable $exception) {
            return $this->errorResponse('Не удалось создать платеж.', 500);
        }

        return $this->successResponse(new PaymentResource($payment), 'Платеж создан');
    }

    /**
     * @group Оплата заказа
     *
     * Проверить статус платежа.
     */
    public function status(string $payment, PaymentService $paymentService, PaymentRepository $paymentRepository): JsonResponse
    {
        $paymentModel = $paymentRepository->findByPublicId($payment);
        if (! $paymentModel) {
            return $this->errorResponse('Платеж не найден.', 404);
        }

        $paymentModel = $paymentService->refreshStatus($paymentModel);

        return $this->successResponse(new PaymentResource($paymentModel));
    }

    /**
     * @group Оплата заказа
     *
     * Возврат платежа.
     */
    public function refund(
        PaymentRefundRequest $request,
        string $payment,
        PaymentService $paymentService,
        PaymentRepository $paymentRepository,
    ): JsonResponse {
        $paymentModel = $paymentRepository->findByPublicId($payment);
        if (! $paymentModel) {
            return $this->errorResponse('Платеж не найден.', 404);
        }

        try {
            $paymentModel = $paymentService->refund($paymentModel, $request->input('amount'));
        } catch (RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage(), 422);
        } catch (Throwable $exception) {
            return $this->errorResponse('Не удалось выполнить возврат.', 500);
        }

        return $this->successResponse(new PaymentResource($paymentModel), 'Возврат выполнен');
    }

    /**
     * @group Оплата заказа
     *
     * Webhook от Альфа-Банка для обновления статуса платежа.
     */
    public function webhook(PaymentWebhookRequest $request, PaymentService $paymentService): JsonResponse
    {
        $payment = $paymentService->handleWebhook($request->validated());

        if (! $payment) {
            return $this->errorResponse('Платеж не найден.', 404);
        }

        return $this->successResponse(new PaymentResource($payment));
    }
}
