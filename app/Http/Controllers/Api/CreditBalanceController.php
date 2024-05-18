<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\CreditBalanceRequest;
use App\Http\Resources\CreditBalanceHistoryResource;
use App\Http\Resources\CreditBalanceResource;
use App\Services\CreditBalance\CreditBalanceService;
use App\Services\Shop\ShopSession;
use App\Http\Controllers\Controller;

class CreditBalanceController extends Controller
{
    protected CreditBalanceService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = CreditBalanceService::factory(ShopSession::getId());
    }

    public function list(CreditBalanceRequest $request, int $customerId)
    {
        $models = $this->service->listByCustomerId($customerId)->get();

        return CreditBalanceHistoryResource::collection($models);
    }

    public function index(CreditBalanceRequest $request, int $customerId)
    {
        $model = $this->service->readByCustomerId($customerId)->get();

        return ! is_null($model) ? new CreditBalanceResource($model) : [];
    }

    public function create(CreditBalanceRequest $request)
    {
        $model = $this->service->create($request->validated())->get();

        return new CreditBalanceResource($model);
    }

    public function read(CreditBalanceRequest $request, int $id)
    {
        $model = $this->service->read($id)->get();

        return new CreditBalanceResource($model);
    }

    public function update(CreditBalanceRequest $request, int $id)
    {
        $model = $this->service->update($id, $request->validated())->get();

        return new CreditBalanceResource($model);
    }

    public function delete(CreditBalanceRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Kreditsaldot kunde inte raderas');
    }
}
