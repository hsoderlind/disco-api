<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\ShopUserRequest;
use App\Services\Shop\ShopSession;
use App\Services\Shop\ShopUserRules;
use App\Services\Shop\ShopUserService;

class ShopUserController extends Controller
{
    protected ShopUserService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = ShopUserService::factory(ShopSession::getId());
    }

    public function index(ShopUserRules $request)
    {
        return $this->service->list()->toResource();
    }

    public function create(ShopUserRequest $request)
    {
        return $this->service
            ->create($request->validated())
            ->inviteUser(inviter: $request->user()->first())
            ->toResource();
    }

    public function read(ShopUserRequest $request, int $id)
    {
        return $this->service->read($id)->toResource();
    }

    public function update(ShopUserRequest $request, int $id)
    {
        return $this->service->update($id, $request->validated())->toResource();
    }

    public function delete(ShopUserRequest $request, int $id)
    {
        $deleted = $this->service
            ->read($id)
            ->removeUserFromShop()
            ->sendRemovedFromShopMail()
            ->delete();

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Användaren kunde inte tas bort.');
    }
}
