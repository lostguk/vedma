<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class PageResource extends JsonResource
{
    /**
     * @param  \App\Models\Page  $resource
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'text' => $this->text,
            'is_visible_in_header' => (bool) $this->is_visible_in_header,
            'is_visible_in_footer' => (bool) $this->is_visible_in_footer,
        ];
    }
}
