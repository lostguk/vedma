<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\HomePageContent */
final class HomePageContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $categories = collect($this->categories ?? []);

        $slides = HeroSlide::query()
            ->active()
            ->ordered()
            ->get();

        return [
            'slides' => HeroSlideResource::collection($slides),
            'categories' => HomePageCategoryResource::collection($categories),
        ];
    }
}
