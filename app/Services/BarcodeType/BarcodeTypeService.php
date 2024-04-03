<?php

namespace App\Services\BarcodeType;

use App\Models\BarcodeType;

class BarcodeTypeService
{
    public function __construct(private int $shopId)
    {
        //
    }

    public function list(bool $withInactive = false)
    {
        $query = BarcodeType::inShop($this->shopId);

        if ($withInactive) {
            $query->where('active', true);
        }

        $query->orderBy('sort_order')
            ->orderBy('label');

        return $query->get();
    }

    public function create(array $data): BarcodeType
    {
        $barcodeType = BarcodeType::create($data);

        return $barcodeType;
    }

    public function update(int $id, array $data): BarcodeType
    {
        $barcodeType = BarcodeType::inShop($this->shopId)->findOrFail($id);
        $barcodeType->fill($data);
        $barcodeType->save();

        return $barcodeType;
    }

    public function delete(int $id)
    {
        $barcodeType = BarcodeType::inShop($this->shopId)->findOrFail($id);

        return $barcodeType->delete();
    }
}
