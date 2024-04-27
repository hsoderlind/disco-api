<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
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
            'sort_order' => $this->sort_order,
            'active' => $this->active,
            'attribute_type_id' => $this->attribute_type_id,
            'attribute_value_id' => $this->attribute_value_id,
            'attribute_type' => new AttributeTypeResource($this->attributeType),
            'attribute_value' => new AttributeValueResource($this->attributeValue),
            'stock' => new AttributeStockResource($this->stock),
        ];
    }
}
