<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShippingResource extends JsonResource
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
            'shipping_method_repository_id' => $this->shipping_method_repository_id,
            'shipping_method_repository' => new ShippingMethodResource($this->whenLoaded('shippingMethodRepository')),
            'shipping_method_name' => $this->shipping_method_name,
            'title' => $this->title,
        ];
    }
}
