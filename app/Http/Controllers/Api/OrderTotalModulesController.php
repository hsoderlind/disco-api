<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderTotalModulesRequest;
use App\Services\Orders\ModulesService;
use App\Services\Shop\ShopSession;

class OrderTotalModulesController extends Controller
{
    protected ModulesService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = ModulesService::factory(ShopSession::getId());
    }

    public function index(OrderTotalModulesRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function install(OrderTotalModulesRequest $request)
    {
        return $this->service->install($request->input('name'))->toResource();
    }

    public function read(OrderTotalModulesRequest $request, string $name)
    {
        return $this->service->read($name)->toResource();
    }

    public function update(OrderTotalModulesRequest $request, string $name)
    {
        return $this->service->update($name)->toResource();
    }

    public function uninstall(OrderTotalModulesRequest $request, string $name)
    {
        return $this->service->uninstall($name)->toResource();
    }
}
