<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'messages_count' => $this->messages_count ?? $this->messages->count(),
            'messages' => $this->whenLoaded('messages', function () {
                return MessageResource::collection($this->messages);
            }),
        ];
    }

    /**
     * Get the status text.
     */
    private function getStatusText(): string
    {
        return match ($this->status) {
            'new' => 'Новый',
            'resolved' => 'Решен',
            'requires_response' => 'Требует ответа',
            default => (string) $this->status,
        };
    }
}
