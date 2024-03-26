<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarcodeTypeRequest;
use App\Http\Resources\BarcodeTypeResource;
use App\Services\BarcodeType\BarcodeTypeService;
use App\Services\Shop\ShopSession;

class BarcodeTypeController extends Controller
{
    private BarcodeTypeService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new BarcodeTypeService(ShopSession::getId());
    }

    public function index(BarcodeTypeRequest $request)
    {
        $barcodeTypes = $this->service->list($request->query('withInactive') === 'true');

        return BarcodeTypeResource::collection($barcodeTypes);
    }

    public function create(BarcodeTypeRequest $request)
    {
        $barcodeType = $this->service->create($request->validated());

        return $barcodeType;
    }

    public function update(BarcodeTypeRequest $request, int $id)
    {
        $barcodeType = $this->service->update($id, $request->validated());

        return $barcodeType;
    }

    public function destroy(BarcodeTypeRequest $request, int $id)
    {
        $this->service->delete($id);
    }
}
