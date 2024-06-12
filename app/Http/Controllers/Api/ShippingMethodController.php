<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShippingMethod\ShippingMethodService;
use App\Services\Shop\ShopSession;
use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodController extends Controller
{
    protected ShippingMethodService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = ShippingMethodService::factory(ShopSession::getId());
    }

    public function index(FormRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function read(FormRequest $request, string $name)
    {
        return $this->service->read($name)->toResource();
    }

    public function update(FormRequest $request, string $name)
    {
        return $this->service->update($name, $request->validated())->toResource();
    }
}
