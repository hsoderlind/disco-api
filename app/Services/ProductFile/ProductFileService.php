<?php

namespace App\Services\ProductFile;

use App\Models\File;
use App\Models\ProductFile;
use App\Services\AbstractService;

class ProductFileService extends AbstractService
{
    public function newModel(array $data)
    {
        $this->data = new ProductFile(
            collect($data)
                ->only(['sort_order'])
                ->toArray()
        );

        $this->data->meta()->save(File::inShop($this->shopId)->findOrFail($data['meta']['id']));

        return $this;
    }
}
