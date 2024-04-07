<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\AttributeValueRequest;
use App\Http\Resources\AttributeValueResource;
use App\Services\AttributeValue\AttributeValueService;
use App\Services\Shop\ShopSession;

class AttributeValueController extends Controller
{
    private AttributeValueService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new AttributeValueService(ShopSession::getId());
    }

    public function list(AttributeValueRequest $request)
    {
        $attributeValues = $this->service->list();

        return AttributeValueResource::collection($attributeValues);
    }

    public function listByAttributeType(AttributeValueRequest $request, int $attributeTypeId)
    {
        $attributeValues = $this->service->listByAttributeType($attributeTypeId);

        return AttributeValueResource::collection($attributeValues);
    }

    public function create(AttributeValueRequest $request)
    {
        $attributeValue = $this->service->create($request->validated());

        return new AttributeValueResource($attributeValue);
    }

    public function read(AttributeValueRequest $request, int $id)
    {
        $attributeValue = $this->service->read($id);

        return new AttributeValueResource($attributeValue);
    }

    public function update(AttributeValueRequest $request, int $id)
    {
        $attributeValue = $this->service->update($id, $request->validated());

        return new AttributeValueResource($attributeValue);
    }

    public function delete(AttributeValueRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Attributvärdet kunde inte raderas.');

        response()->setStatusCode(HttpResponseCode::OK)->send();
    }
}
