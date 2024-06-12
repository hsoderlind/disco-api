<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'configuration' => $this->configuration,
            'fee' => $this->fee,
            'sort_order' => $this->sort_order,
            'active' => $this->active,
            'component' => $this->component,
            'admin_component' => $this->admin_component,
        ];
    }
}
