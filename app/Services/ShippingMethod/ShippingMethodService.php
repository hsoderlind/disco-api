<?php

namespace App\Services\ShippingMethod;

use App\Http\Resources\ShippingMethodResource;
use App\Models\ShippingMethodRepository;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShippingMethodService extends AbstractService
{
    protected $resource = ShippingMethodResource::class;

    public function list()
    {
        $this->data = ShippingMethodRepository::inShop($this->shopId)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return $this;
    }

    public function read(string $name)
    {
        $this->data = ShippingMethodRepository::inShop($this->shopId)
            ->where('name', $name)
            ->firstOrFail();

        return $this;
    }

    public function update(string $name, array $data)
    {
        Log::info('DATA: '.json_encode($data));
        $this->data = DB::transaction(function () use ($name, $data) {
            /** @var \App\Models\ShippingMethodRepository */
            $model = $this->read($name)->get();
            $model->update($data);

            return $model;
        });

        return $this;
    }
}
