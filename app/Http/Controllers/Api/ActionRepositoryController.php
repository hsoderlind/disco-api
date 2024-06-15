<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatusRequest;
use App\Services\OrderStatus\ActionRepositoryService;
use App\Services\Shop\ShopSession;

class ActionRepositoryController extends Controller
{
    protected ActionRepositoryService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = ActionRepositoryService::factory(ShopSession::getId());
    }

    public function index(OrderStatusRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function read(OrderStatusRequest $request, string $name)
    {
        return $this->service->read($name)->toResource();
    }
}
