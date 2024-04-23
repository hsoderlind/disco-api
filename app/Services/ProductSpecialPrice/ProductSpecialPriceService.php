<?php

namespace App\Services\ProductSpecialPrice;

use App\Http\Resources\ProductSpecialPriceResource;
use App\Models\ProductSpecialPrice;
use App\Services\AbstractService;

class ProductSpecialPriceService extends AbstractService
{
    protected $resource = ProductSpecialPriceResource::class;

    public function initModel(array $data)
    {
        $this->data = new ProductSpecialPrice(
            collect($data)
                ->only(['special_price', 'entry_date', 'expiration_date'])
                ->toArray()
        );
    }
}
