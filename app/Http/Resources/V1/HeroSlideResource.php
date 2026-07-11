<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\HeroSlide */
final class HeroSlideResource extends JsonResource
{
    private function resolveImageUrl(?string $imagePath): ?string
    {
        if (! $imagePath) {
            return null;
        }

        $optimizedWebpPath = preg_replace('/\.(png|jpe?g)$/i', '.webp', $imagePath);

        if ($optimizedWebpPath && $optimizedWebpPath !== $imagePath && Storage::disk('public')->exists($optimizedWebpPath)) {
            return Storage::disk('public')->url($optimizedWebpPath);
        }

        return Storage::disk('public')->url($imagePath);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'accent' => $this->accent,
            'subtitle' => $this->subtitle,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
            'image' => $this->resolveImageUrl($this->image_path),
        ];
    }
}
