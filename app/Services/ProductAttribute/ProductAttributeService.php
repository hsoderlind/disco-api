<?php

namespace App\Services\ProductAttribute;

use App\Models\AttributeProduct;
use App\Models\Product;
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

    public function create(Product $product, array $data): AttributeProduct
    {

        return DB::transaction(function () use ($product, $data) {
            /** @var AttributeProduct $productAttribute */
            $productAttribute = new AttributeProduct([
                'sort_order' => $data['sort_order'],
                'active' => $data['active'],
            ]);

            $productAttribute->attributeType()->associate($data['attribute_type_id']);
            $productAttribute->attributeValue()->associate($data['attribute_value_id']);
            $productAttribute->product()->associate($product->getKey());

            $productAttribute->save();

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

    public function updateOrCreate(Product $product, array $data): AttributeProduct
    {
        if (isset($data['id'])) {
            return $this->update($data['id'], $data);
        } else {
            return $this->create($product, $data);
        }
    }

    public function delete(int $id): bool
    {
        $attributeProduct = AttributeProduct::inShop($this->shopId)->findOrFail($id);

        return $attributeProduct->delete();
    }
}
