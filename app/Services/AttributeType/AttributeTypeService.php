<?php

namespace App\Services\AttributeType;

use App\Models\AttributeType;

class AttributeTypeService
{
    public function __construct(private readonly int $shopId)
    {

    }

    public function list(bool $includeInactive = false, ?array $withRelations = null)
    {
        $query = AttributeType::inShop($this->shopId);

        if (! is_null($withRelations)) {
            $query->with($withRelations);
        }

        if (! $includeInactive) {
            $query->where('active', true);
        }

        return $query
            ->orderBy('label')
            ->get();
    }

    public function listByProduct(int $productId, bool $includeInactive = false, ?array $withRelations = null)
    {
        $query = AttributeType::inShop($this->shopId)
            ->whereHas('products', fn ($query) => $query->where('products.id', $productId));

        if (! is_null($withRelations)) {
            $query->with($withRelations);
        }

        if (! $includeInactive) {
            $query->where('active', true);
        }

        return $query->get();
    }

    public function read(int $id, ?array $withRelations = null): AttributeType
    {
        $query = AttributeType::inShop($this->shopId);

        if (! is_null($withRelations)) {
            $query->with($withRelations);
        }

        return $query->findOrFail($id);
    }

    public function create(array $data): AttributeType
    {
        $attributeType = AttributeType::create($data);

        return $attributeType;
    }

    public function update(int $id, array $data): AttributeType
    {
        $attributeType = AttributeType::inShop($this->shopId)->findOrFail($id);
        $attributeType->update($data);

        return $attributeType;
    }

    public function delete(int $id): bool
    {
        $attributeType = AttributeType::inShop($this->shopId)->findOrFail($id);

        return $attributeType->delete();
    }
}
