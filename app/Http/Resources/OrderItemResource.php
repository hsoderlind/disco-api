<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'tax_id' => $this->tax_id,
            'tax' => new TaxResource($this->whenLoaded('tax')),
            'product_name' => $this->product_name,
            'item_number' => $this->item_number,
            'price' => $this->price,
            'total' => $this->total,
            'vat' => $this->vat,
            'tax_value' => $this->tax_value,
            'quantity' => $this->quantity,
        ];
    }
}
