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
        return $this->service->list($request->query('includeInactive') === 'true')->toResource();
    }

    public function install(PaymentMethodRequest $request)
    {
        return $this->service->install($request->validated())->toResource();
    }

    public function read(PaymentMethodRequest $request, string $name)
    {
        return $this->service->read($name)->toResource();
    }

    public function update(PaymentMethodRequest $request, string $name)
    {
        return $this->service->update($name, $request->validated())->toResource();
    }

    public function updateCore(PaymentMethodRequest $request, string $name)
    {
        return $this->service->updateCore($name)->toResource();
    }

    public function uninstall(PaymentMethodRequest $request, string $name)
    {
        $deleted = $this->service->uninstall($name);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Betalningsmetoden kunde inte avinstalleras');
    }
}
