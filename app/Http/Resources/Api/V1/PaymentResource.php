<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->public_id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_url' => $this->payment_url,
            'error_message' => $this->error_message,
            'fiscal' => $this->fiscalSummary(),
            'paid_at' => $this->paid_at?->toDateTimeString(),
            'refunded_at' => $this->refunded_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }

    /**
     * Краткий статус чеков из getReceiptStatus (если уже запрашивался).
     *
     * @return array{receipts: list<array{status: int|null, uuid: string|null}>, error: string|null}|null
     */
    private function fiscalSummary(): ?array
    {
        $receiptResponse = $this->payload['receiptStatusResponse'] ?? null;
        if (! is_array($receiptResponse)) {
            return null;
        }

        $receipts = [];
        foreach ($receiptResponse['receipt'] ?? [] as $receipt) {
            if (! is_array($receipt)) {
                continue;
            }

            $receipts[] = [
                'status' => isset($receipt['receiptStatus']) ? (int) $receipt['receiptStatus'] : null,
                'uuid' => isset($receipt['uuid']) ? (string) $receipt['uuid'] : null,
            ];
        }

        return [
            'receipts' => $receipts,
            'error' => isset($receiptResponse['errorMessage']) ? (string) $receiptResponse['errorMessage'] : null,
        ];
    }
}
