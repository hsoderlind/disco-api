<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeStockResource extends JsonResource
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
            'sku' => $this->sku,
            'stock_unit' => $this->stock_unit,
            'out_of_stock_message' => $this->out_of_stock_message,
            'available_at' => $this->available_at,
            'allow_order_out_of_stock' => $this->allow_order_out_of_stock,
            'initial_quantity' => $this->initial_quantity,
            'reserved_quantity' => $this->reserved_quantity,
            'sold_quantity' => $this->sold_quantity,
        ];
    }
}
