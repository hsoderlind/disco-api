<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\AttributeTypeRequest;
use App\Http\Resources\AttributeTypeResource;
use App\Services\AttributeType\AttributeTypeService;
use App\Services\Shop\ShopSession;

class AttributeTypeController extends Controller
{
    private AttributeTypeService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new AttributeTypeService(ShopSession::getId());
    }

    public function list(AttributeTypeRequest $request)
    {
        $attributeTypes = $this->service->list($request->query('includeInactive') === 'true');

        return AttributeTypeResource::collection($attributeTypes);
    }

    public function listByProduct(AttributeTypeRequest $request, int $productId)
    {
        $attributeTypes = $this->service->listByProduct($productId, $request->query('includeInactive') === 'true');

        return AttributeTypeResource::collection($attributeTypes);
    }

    public function create(AttributeTypeRequest $request)
    {
        $attributeType = $this->service->create($request->validated());

        return new AttributeTypeResource($attributeType);
    }

    public function read(AttributeTypeRequest $request, int $id)
    {
        $attributeType = $this->service->read($id);

        return new AttributeTypeResource($attributeType);
    }

    public function update(AttributeTypeRequest $request, int $id)
    {
        $attributeType = $this->service->update($id, $request->validated());

        return $attributeType;
    }

    public function delete(AttributeTypeRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        if (! $deleted) {
            abort(HttpResponseCode::METHOD_NOT_ALLOWED, 'Attributtypen raderades inte.');
        }

        response()->setStatusCode(HttpResponseCode::OK)->send();
    }
}
