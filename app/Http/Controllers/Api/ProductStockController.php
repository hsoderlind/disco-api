<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStockRequest;
use App\Services\ProductStock\ProductStockService;
use App\Services\Shop\ShopSession;

class ProductStockController extends Controller
{
    protected ProductStockService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = ProductStockService::factory(ShopSession::getId());
    }

    public function index(ProductStockRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function read(ProductStockRequest $request, int $id)
    {
        return $this->service->read($id)->toResource();
    }

    public function update(ProductStockRequest $request, int $id)
    {
        return $this->service->update($id, $request->validated())->toResource();
    }
}
