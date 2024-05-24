<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\RoleRequest;
use App\Services\Role\RoleService;
use App\Services\Shop\ShopSession;

class RoleController extends Controller
{
    protected RoleService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = RoleService::factory(ShopSession::getId());
    }

    public function index(RoleRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function create(RoleRequest $request)
    {
        return $this->service->create($request->validated())->toResource();
    }

    public function read(RoleRequest $request, int $id)
    {
        return $this->service->read($id)->toResource();
    }

    public function update(RoleRequest $request, int $id)
    {
        return $this->service->update($id, $request->validated())->toResource();
    }

    public function delete(RoleRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Produktbilden kunde inte raderas');
    }
}
