<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderTotalRepositoryRequest;
use App\Services\Orders\OrderTotalRepositoryService;
use App\Services\Shop\ShopSession;

class OrderTotalRepositoryController extends Controller
{
    protected OrderTotalRepositoryService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = OrderTotalRepositoryService::factory(ShopSession::getId());
    }

    public function index(OrderTotalRepositoryRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function read(OrderTotalRepositoryRequest $request, string $name)
    {
        return $this->service->read($name)->toResource();
    }

    public function update(OrderTotalRepositoryRequest $request, string $name)
    {
        return $this->service->update($name, $request->validated())->toResource();
    }
}
