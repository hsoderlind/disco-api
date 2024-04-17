<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\ProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Services\ProductImage\ProductImageService;
use App\Services\Shop\ShopSession;

class ProductImageController extends Controller
{
    protected ProductImageService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new ProductImageService(ShopSession::getId());
    }

    public function index(ProductImageRequest $request, int $productId)
    {
        $models = $this->service->list($productId);

        return ProductImageResource::collection($models);
    }

    public function create(ProductImageRequest $request)
    {
        $model = $this->service->create($request->validated());

        return new ProductImageResource($model);
    }

    public function read(ProductImageRequest $request, int $id)
    {
        $model = $this->service->get($id);

        return new ProductImageResource($model);
    }

    public function update(ProductImageRequest $request, int $id)
    {
        $model = $this->service->update($id, $request->validated());

        return new ProductImageResource($model);
    }

    public function delete(ProductImageRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Produktbilden kunde inte raderas');
    }
}
