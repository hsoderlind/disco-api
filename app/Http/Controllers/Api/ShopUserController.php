<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
