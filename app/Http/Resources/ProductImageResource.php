<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
            'use_as_cover' => $this->use_as_cover,
            'sort_order' => $this->sort_order,
            'meta' => new FileResource($this->meta),
        ];
    }
}
