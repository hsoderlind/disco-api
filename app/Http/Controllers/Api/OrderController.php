<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderStatusHistoryRequest;
use App\Services\Orders\OrderService;
use App\Services\Shop\ShopSession;

class OrderController extends Controller
{
    protected OrderService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = OrderService::factory(ShopSession::getId());
    }

    public function index(OrderRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function create(OrderRequest $request)
    {
        return $this->service->create($request->validated())->toResource();
    }

    public function read(OrderRequest $request, int $id)
    {
        return $this->service->read($id)->toResource();
    }

    public function update(OrderStatusHistoryRequest $request, int $id)
    {
        return $this->service->updateOrderStatus(
            $id,
            $request->validated()
        )->toResource();
    }
}
