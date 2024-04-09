<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\TaxRequest;
use App\Http\Resources\TaxResource;
use App\Services\Shop\ShopSession;
use App\Services\Tax\TaxService;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    private TaxService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new TaxService(ShopSession::getId());
    }

    public function index(Request $request)
    {
        if ($request->query('withInactive')) {
            $taxes = $this->service->listWithInactive();
        } else {
            $taxes = $this->service->list();
        }

        return TaxResource::collection($taxes);
    }

    public function create(TaxRequest $request)
    {
        $tax = $this->service->create($request->validated());

        return new TaxResource($tax);
    }

    public function show(TaxRequest $request, int $id)
    {
        $tax = $this->service->read($id);

        return new TaxResource($tax);
    }

    public function update(TaxRequest $request, int $id)
    {
        $tax = $this->service->update($id, $request->validated());

        return new TaxResource($tax);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if (! $deleted) {
            abort(HttpResponseCode::METHOD_NOT_ALLOWED, 'Momsklassen raderades inte.');
        }

        response()->setStatusCode(HttpResponseCode::OK)->send();
    }
}
