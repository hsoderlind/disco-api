<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductStockResource extends JsonResource
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
            'initial_quantity' => $this->initial_quantity,
            'reserved_quantity' => $this->reserved_quantity,
            'sold_quantity' => $this->sold_quantity,
            'min_order_quantity' => $this->min_order_quantity,
            'out_of_stock_message' => $this->out_of_stock_message,
            'allow_order_out_of_stock' => $this->allow_order_out_of_stock,
            'send_email_out_of_stock' => $this->send_email_out_of_stock,
            'in_stock_message' => $this->in_stock_message,
            'available_at' => $this->available_at,
            'disposable_quantity' => $this->disposable_quantity,
            'approx_disposable_quantity' => $this->approx_disposable_quantity,
        ];
    }
}
