<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Services\Permissions\PermissionControllerService;
use App\Services\Shop\ShopSession;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected PermissionControllerService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = PermissionControllerService::factory(ShopSession::getId());
    }

    public function index(PermissionRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function userPermissions(Request $request)
    {
        return $this->service->userPermissions($request->user()->first())->toResource();
    }

    public function listByRole(PermissionRequest $request, int $roleId)
    {
        return $this->service->listByRole($roleId)->toResource();
    }

    public function update(PermissionRequest $request, int $roleId)
    {
        return $this->service->syncToRole($roleId, $request->input('permissions'))->toResource();
    }
}
