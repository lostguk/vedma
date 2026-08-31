<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PromoBanner */
final class PromoBannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kicker' => $this->kicker,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'discount_value' => $this->discount_value,
            'discount_caption' => $this->discount_caption,
            'promo_code' => $this->promo_code,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
            'ends_at' => $this->ends_at?->toIso8601String(),
        ];
    }
}
