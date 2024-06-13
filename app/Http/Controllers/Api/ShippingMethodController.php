<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingMethodRequest;
use App\Services\ShippingMethod\ShippingMethodService;
use App\Services\Shop\ShopSession;

class ShippingMethodController extends Controller
{
    protected ShippingMethodService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = ShippingMethodService::factory(ShopSession::getId());
    }

    public function index(ShippingMethodRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function read(ShippingMethodRequest $request, string $name)
    {
        return $this->service->read($name)->toResource();
    }

    public function update(ShippingMethodRequest $request, string $name)
    {
        return $this->service->update($name, $request->validated())->toResource();
    }
}
