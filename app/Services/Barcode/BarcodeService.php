<?php

namespace App\Services\Barcode;

use App\Models\Barcode;
use App\Services\BarcodeType\BarcodeTypeService;

class BarcodeService
{
    public function __construct(private readonly int $shopId)
    {
        //
    }

    public function create(array $data)
    {
        $barcodeTypeService = new BarcodeTypeService($this->shopId);

        $barcode = new Barcode(['value' => $data['value']]);
        $barcode->barcodeType()->associate($barcodeTypeService->get($data['barcode_type_id']));

        $barcode->save();

        return $barcode;
    }

    public function get(int $id): Barcode
    {
        return Barcode::inShop($this->shopId)->findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $barcodeTypeService = new BarcodeTypeService($this->shopId);
        $barcode = $this->get($id);
        $barcode->value = $data['value'];

        if ($barcode->barcode_type_id !== $data['barcode_type_id']) {
            $barcode->barcodeType()->associate($barcodeTypeService->get($data['barcode_type_id']));
        }

        $barcode->save();

        return $barcode;
    }

    public function updateOrCreate(array $data): Barcode
    {
        if (isset($data['id'])) {
            return $this->update($data['id'], $data);
        } else {
            return $this->create($data);
        }
    }

    public function delete(int $id)
    {
        $barcode = $this->get($id);
        $deleted = $barcode->delete();

        return $deleted;
    }
}
