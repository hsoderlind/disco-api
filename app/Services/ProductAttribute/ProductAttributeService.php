<?php

namespace App\Services\ProductAttribute;

use App\Models\AttributeProduct;
use App\Services\ProductAttributeStock\ProductAttributeStockService;
use Illuminate\Support\Facades\DB;

class ProductAttributeService
{
    public function __construct(private readonly int $shopId)
    {

    }

    public function create(array $data): AttributeProduct
    {

        return DB::transaction(function () use ($data) {
            $productAttribute = AttributeProduct::create([
                'sort_order' => $data['sort_order'],
                'active' => $data['active'],
            ]);

            $productAttribute->attributeType()->associate($data['attribute_type_id']);
            $productAttribute->attributeValue()->associate($data['attribute_value_id']);

            if (isset($data['stock'])) {
                $attributeStock = (new ProductAttributeStockService($this->shopId))->create($data['stock']);
                $productAttribute->stock()->save($attributeStock);
            }

            return $productAttribute;
        });
    }
}
