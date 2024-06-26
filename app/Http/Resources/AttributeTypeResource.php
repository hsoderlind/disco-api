<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeTypeResource extends JsonResource
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
            'label' => $this->label,
            'active' => $this->active,
            // ! Throws error
            // 'attribute_values' => $this->whenLoaded('attributeValues', AttributeValueResource::collection($this->attribute_values)),
        ];
    }
}
