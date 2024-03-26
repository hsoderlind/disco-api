<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarcodeTypeResource extends JsonResource
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
            'format' => $this->format,
            'sort_order' => $this->sort_order,
            'active' => $this->active,
            'barcodes' => BarcodeResource::collection($this->whenLoaded('barcodes')),
        ];
    }
}
