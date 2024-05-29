<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\PaymentMethodRequest;
use App\Services\PaymentMethod\PaymentMethodService;
use App\Services\Shop\ShopSession;

class PaymentMethodController extends Controller
{
    protected PaymentMethodService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = PaymentMethodService::factory(ShopSession::getId());
    }

    public function index(PaymentMethodRequest $request)
    {
        return $this->service->list()->toResource();
    }

    public function install(PaymentMethodRequest $request)
    {
        return $this->service->install($request->validated())->toResource();
    }

    public function read(PaymentMethodRequest $request, int $id)
    {
        return $this->service->read($id)->toResource();
    }

    public function update(PaymentMethodRequest $request, int $id)
    {
        return $this->service->update($id, $request->validated())->toResource();
    }

    public function uninstall(PaymentMethodRequest $request, int $id)
    {
        $deleted = $this->service->uninstall($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Betalningsmetoden kunde inte avinstalleras');
    }
}
