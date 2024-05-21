<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceSettingsRequest;
use App\Http\Requests\LogotypeRequest;
use App\Http\Resources\InvoiceSettingsResource;
use App\Services\InvoiceSettings\InvoiceSettingsService;
use App\Services\Shop\ShopSession;

class InvoiceSettingsController extends Controller
{
    protected InvoiceSettingsService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = InvoiceSettingsService::factory(ShopSession::getId());
    }

    public function index(InvoiceSettingsRequest $request)
    {
        $model = $this->service->read()->get();

        return new InvoiceSettingsResource($model);
    }

    public function update(InvoiceSettingsRequest $request)
    {
        $model = $this->service->update($request->validated())->get();

        return new InvoiceSettingsResource($model);
    }

    public function logotype(LogotypeRequest $request)
    {
        $model = $this->service->setLogotype($request->validated())->get();

        return new InvoiceSettingsResource($model);
    }
}
