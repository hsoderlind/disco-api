<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\OrderStatusRequest;
use App\Services\OrderStatus\OrderStatusService;
use App\Services\Shop\ShopSession;

class OrderStatusController extends Controller
{
    protected OrderStatusService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = OrderStatusService::factory(ShopSession::getId());
    }

    public function index(OrderStatusRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function create(OrderStatusRequest $request)
    {
        return $this->service->create($request->validated())->toResource();
    }

    public function read(OrderStatusRequest $request, int $id)
    {
        return $this->service->read($id)->toResource();
    }

    public function update(OrderStatusRequest $request, int $id)
    {
        return $this->service->update($id, $request->validated())->toResource();
    }

    public function delete(OrderStatusRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Orderstatusen kunde inte raderas');
    }
}
