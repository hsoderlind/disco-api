<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSpecialPriceResource extends JsonResource
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
            'special_price' => $this->special_price,
            'entry_date' => $this->entry_date,
            'expiration_date' => $this->expiration_date,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
