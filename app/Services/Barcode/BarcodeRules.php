<?php

namespace App\Services\Barcode;

use App\Validation\Rules;

class BarcodeRules extends Rules
{
    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:barcodes,id',
            'barcode_type_id' => 'required|integer|numeric|exists:barcode_types,id',
            'value' => 'required|string|max:255',
        ];
    }
}
