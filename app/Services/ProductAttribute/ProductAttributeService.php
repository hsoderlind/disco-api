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

    public function list(?array $withRelations = null)
    {
        if (! $withRelations) {
            return AttributeProduct::inShop($this->shopId)->get();
        } else {
            return AttributeProduct::inShop($this->shopId)->with($withRelations)->get();
        }
    }

    public function read(int $id, ?array $withRelations = null): AttributeProduct
    {
        $attributeProduct = AttributeProduct::inShop($this->shopId)->findOrFail($id);

        if (is_array($withRelations)) {
            $attributeProduct->load($withRelations);
        }

        return $attributeProduct;
    }

    public function create(array $data): AttributeProduct
    {

        return DB::transaction(function () use ($data) {
            /** @var AttributeProduct $productAttribute */
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

    public function update(int $id, array $data): AttributeProduct
    {
        return DB::transaction(function () use ($id, $data) {
            $attributeProduct = AttributeProduct::inShop($this->shopId)->findOrFail($id);
            $attributeProduct->sort_order = $data['sort_order'];
            $attributeProduct->active = $data['active'];

            return $attributeProduct;
        });
    }

    public function delete(int $id): bool
    {
        $attributeProduct = AttributeProduct::inShop($this->shopId)->findOrFail($id);

        return $attributeProduct->delete();
    }
}
