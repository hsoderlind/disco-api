<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentModulesRequest;
use App\Services\PaymentMethod\PaymentModulesService;
use App\Services\Shop\ShopSession;

class PaymentModulesController extends Controller
{
    protected PaymentModulesService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = PaymentModulesService::factory(ShopSession::getId());
    }

    public function index(PaymentModulesRequest $request)
    {
        return $this->service->list()->toResource();
    }
}
