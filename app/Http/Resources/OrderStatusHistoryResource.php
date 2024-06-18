<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusHistoryResource extends JsonResource
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
            'old_status_id' => $this->old_status_id,
            'old_status' => new OrderStatusResource($this->whenLoaded('oldStatus')),
            'new_status_id' => $this->new_status_id,
            'new_status' => new OrderStatusResource($this->newStatus),
            'note' => new NoteResource($this->whenLoaded('note')),
            'created_at' => $this->created_at,
        ];
    }
}
