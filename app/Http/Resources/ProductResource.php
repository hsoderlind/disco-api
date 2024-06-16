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
            'state' => $this->state,
            'tax_id' => $this->tax_id,
            'item_number' => $this->item_number,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->sellingPrice(),
            'reference' => $this->reference,
            'supplier_reference' => $this->supplier_reference,
            'condition' => $this->condition,
            'name' => $this->name,
            'summary' => $this->summary,
            'description' => $this->description,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'tax' => new TaxResource($this->tax),
            'barcodes' => BarcodeResource::collection($this->whenLoaded('barcodes')),
            'supplier' => new SupplierResource($this->supplier),
            'manufacturer' => new ManufacturerResource($this->manufacturer),
            'special_prices' => ProductSpecialPriceResource::collection($this->whenLoaded('specialPrices')),
            'current_special_price' => new ProductSpecialPriceResource($this->whenLoaded('currentSpecialPrice')),
            'stock' => new ProductStockResource($this->stock),
            'product_attributes' => ProductAttributeResource::collection($this->whenLoaded('productAttributes')),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'files' => ProductFileResource::collection($this->whenLoaded('files')),
        ];
    }
}
