<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\Customer\CustomerService;
use App\Services\Shop\ShopSession;

class CustomerController extends Controller
{
    protected CustomerService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = CustomerService::factory(ShopSession::getId());
    }

    public function index(CustomerRequest $request)
    {
        $models = $this->service->list()->get();

        return CustomerResource::collection($models);
    }

    public function create(CustomerRequest $request)
    {
        $model = $this->service->create($request->validated())->get();

        return new CustomerResource($model);
    }

    public function read(CustomerRequest $request, int $id)
    {
        $model = $this->service->read($id)->get();

        return new CustomerResource($model);
    }

    public function update(CustomerRequest $request, int $id)
    {
        $model = $this->service->update($id, $request->validated())->get();

        return new CustomerResource($model);
    }

    public function delete(CustomerRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Kunden kunde inte raderas');
    }
}
