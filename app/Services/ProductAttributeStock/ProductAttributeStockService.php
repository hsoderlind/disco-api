<?php

namespace App\Services\ProductAttributeStock;

use App\Models\AttributeStock;

class ProductAttributeStockService
{
    public function __construct(private readonly int $shopId)
    {

    }

    public function create(array $data): AttributeStock
    {
        $attributeStock = AttributeStock::create($data);

        return $attributeStock;
    }
}
