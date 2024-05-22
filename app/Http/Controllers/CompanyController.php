<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Services\Company\CompanyService;
use App\Services\Shop\ShopSession;

class CompanyController extends Controller
{
    protected CompanyService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = CompanyService::factory(ShopSession::getId());
    }

    public function index(CompanyRequest $request)
    {
        return $this->service->read()->toResource();
    }

    public function create(CompanyRequest $request)
    {
        return $this->service->create($request->validated())->toResource();
    }

    public function read(CompanyRequest $request, int $id)
    {
        return $this->service->read($id)->toResource();
    }

    public function update(CompanyRequest $request, int $id)
    {
        return $this->service->update($id, $request->validated())->toResource();
    }
}
