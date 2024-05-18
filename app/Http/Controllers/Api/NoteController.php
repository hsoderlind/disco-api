<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Services\Note\NoteService;
use App\Services\Shop\ShopSession;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    protected NoteService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = NoteService::factory(ShopSession::getId());
    }

    public function index(NoteRequest $request, string $resource, int $id)
    {
        $models = $this->service->setRelationModel($resource, $id)->list()->get();

        return NoteResource::collection($models);
    }

    public function create(NoteRequest $request, string $resource, int $id)
    {
        $model = $this->service->setRelationModel($resource, $id)->create($request->validated())->get();

        return new NoteResource($model);
    }

    public function read(NoteRequest $request, int $id)
    {
        $model = $this->service->read($id)->get();

        return new NoteResource($model);
    }

    public function update(NoteRequest $request, int $id)
    {
        $model = $this->service->update($id, $request->validated())->get();

        return new NoteResource($model);
    }

    public function delete(NoteRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Anteckningen kunde inte raderas');
    }
}
