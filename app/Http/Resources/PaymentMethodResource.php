<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
            'sort_order' => $this->sort_order,
            'active' => $this->active,
            'fee' => $this->fee,
            'component' => $this->component,
            'admin_component' => $this->admin_component,
            'configuration' => $this->configuration,
            'logotype' => new LogotypeResource($this->logotype),
            'version' => $this->version,
        ];
    }
}
