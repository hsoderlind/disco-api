<?php

namespace App\Services\ProductAttributeStock;

use App\Models\AttributeProduct;
use App\Models\AttributeStock;

class ProductAttributeStockService
{
    public function __construct(private readonly int $shopId)
    {

    }

    public function create(AttributeProduct $attributeProduct, array $data): AttributeStock
    {
        /** @var \App\Models\AttributeStock $attributeStock */
        $attributeStock = new AttributeStock(
            collect($data)
                ->only([
                    'sku',
                    'stock_unit',
                    'out_of_stock_message',
                    'available_at',
                    'allow_order_out_of_stock',
                    'initial_quantity',
                    'reserved_quantity',
                    'sold_quantity',
                ])
                ->toArray()
        );

        $attributeStock->available_at = $data['available_at'];

        $attributeStock->attributeProduct()->associate($attributeProduct);

        $attributeStock->save();

        return $attributeStock;
    }
}
