<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'price' => $this->price,
            'reference' => $this->reference,
            'supplier_reference' => $this->supplier_reference,
            'available_for_order' => $this->available_for_order,
            'available_at' => $this->available_at,
            'condition' => $this->condition,
            'name' => $this->name,
            'summary' => $this->summary,
            'description' => $this->description,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'tax' => new TaxResource($this->tax),
            'barcodes' => BarcodeResource::collection($this->whenLoaded('barcodes')),
            'supplier' => new SupplierResource($this->supplier),
            'mmanufacturer' => new ManufacturerResource($this->manufacturer),
            'special_prices' => ProductSpecialPriceResource::collection($this->whenLoaded('specialPrices')),
        ];
    }
}
