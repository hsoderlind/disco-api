<?php

namespace App\Services\AttributeValue;

use App\Models\AttributeValue;

class AttributeValueService
{
    public function __construct(private readonly int $shopId)
    {

    }

    public function list()
    {
        return AttributeValue::inShop($this->shopId)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();
    }

    public function listByAttributeType(int $attributeTypeId)
    {
        return AttributeValue::inShop($this->shopId)
            ->whereHas('attributeType', fn ($query) => $query->where('attribute_types.id', $attributeTypeId))
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();
    }

    public function create(array $data)
    {
        /** @var AttributeValue $attributeValue */
        $attributeValue = new AttributeValue([
            'label' => $data['label'],
            'sort_order' => $data['sort_order'],
        ]);
        $attributeValue->attributeType()->associate($data['attribute_type_id']);
        $attributeValue->save();

        return $attributeValue;
    }

    public function read(int $id)
    {
        return AttributeValue::inShop($this->shopId)->findOrFail($id);
    }

    public function update(int $id, array $data): false|AttributeValue
    {
        $attributeValue = AttributeValue::inShop($this->shopId)->findOrFail($id);
        $updated = $attributeValue->update([
            'label' => $data['label'],
            'sort_order' => $data['sort_order'],
        ]);

        if (! $updated) {
            return false;
        }

        $attributeValue->attributeType()->associate($data['attribute_type_id']);

        return $attributeValue;
    }

    public function delete(int $id): bool
    {
        $attributeValue = AttributeValue::inShop($this->shopId)->findOrFail($id);

        return $attributeValue->delete();
    }
}
