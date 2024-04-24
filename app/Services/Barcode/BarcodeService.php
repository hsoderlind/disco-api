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
}
