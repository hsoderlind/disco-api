<?php

namespace App\Services\PaymentMethod;

use App\Exceptions\ShopContextRequiredException;
use App\Http\Resources\PaymentModulesCollection;
use App\Http\Resources\PaymentModulesResource;
use App\Services\AbstractService;

class PaymentModulesService extends AbstractService
{
    protected $resource = PaymentModulesResource::class;

    protected $collectionResource = PaymentModulesCollection::class;

    protected function boot()
    {
        if (is_null($this->shopId)) {
            throw new ShopContextRequiredException('Shop context required');
        }
    }

    public function list()
    {
        $this->data = require __DIR__.'/modules.php';

        return $this;
    }
}
