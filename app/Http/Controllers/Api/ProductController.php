<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\Product\ProductService;
use App\Services\Product\ProductState;
use App\Services\Shop\ShopSession;

class ProductController extends Controller
{
    private ProductService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new ProductService(ShopSession::getId());
    }

    public function index(ProductRequest $request)
    {
        if ($request->query('state')) {
            $products = $request->query('state') == ProductState::Published->value
                ? $this->service->listPublished((int) $request->query('category'))
                : $this->service->listDraft((int) $request->query('category'));
        } else {
            $products = $this->service->list((int) $request->query('category'));
        }

        return ProductResource::collection($products);
    }

    public function listAsSummary(ProductRequest $request)
    {
        $products = $this->service->listAsSummary(
            (int) $request->query('category'),
            $request->query('includeSubcategories') === 'true',
            $request->query('state')
        );

        return ProductResource::collection($products);
    }

    public function create(ProductRequest $request)
    {
        $product = $this->service->create($request->validated());

        return new ProductResource($product);
    }

    public function show(ProductRequest $request, int $id)
    {
        $product = $this->service->read($id);

        return new ProductResource($product);
    }

    public function update(ProductRequest $request, int $id)
    {
        $product = $this->service->update($id, $request->validated());

        return new ProductResource($product);
    }

    public function destroy(ProductRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Produkten raderades inte.');
    }
}
