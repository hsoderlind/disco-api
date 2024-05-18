<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\MetadataRequest;
use App\Http\Resources\MetadataResource;
use App\Services\Metadata\MetadataService;
use App\Services\Shop\ShopSession;

class MetadataController extends Controller
{
    protected MetadataService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = MetadataService::factory(ShopSession::getId());
    }

    public function index(MetadataRequest $request, string $resource, int $resourceId)
    {
        $models = $this->service->setRelationModel($resource, $resourceId)->list()->get();

        return MetadataResource::collection($models);
    }

    public function create(MetadataRequest $request, string $resource, int $resourceId)
    {
        $model = $this->service->setRelationModel($resource, $resourceId)->create($request->validated())->get();

        return new MetadataResource($model);
    }

    public function read(MetadataRequest $request, int $id)
    {
        $model = $this->service->read($id)->get();

        return new MetadataResource($model);
    }

    public function update(MetadataRequest $request, int $id)
    {
        $model = $this->service->update($id, $request->validated())->get();

        return new MetadataResource($model);
    }

    public function delete(MetadataRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Metadatan kunde inte raderas');
    }
}
