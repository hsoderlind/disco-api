<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingModulesRequest;
use App\Services\ShippingMethod\ModuleRepositoryService;
use App\Services\Shop\ShopSession;

class ShippingModulesController extends Controller
{
    protected ModuleRepositoryService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = ModuleRepositoryService::factory(ShopSession::getId());
    }

    public function index(ShippingModulesRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function install(ShippingModulesRequest $request)
    {
        return $this->service->install($request->validated('name'))->toResource();
    }

    public function read(ShippingModulesRequest $request, string $name)
    {
        return $this->service->read($name)->toResource();
    }

    public function update(ShippingModulesRequest $request, string $name)
    {
        return $this->service->update($name)->toResource();
    }

    public function uninstall(ShippingModulesRequest $request, string $name)
    {
        return $this->service->uninstall($name)->toResource();
    }
}
