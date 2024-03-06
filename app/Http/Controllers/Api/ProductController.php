<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Shop\ShopSession;

class ProductController extends Controller
{
    private $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new ProductService(ShopSession::getId());
    }

    public function index()
    {
        $products = $this->service->list();

        return ProductResource::collection($products);
    }

    public function create(ProductRequest $request)
    {
        $product = $this->service->create($request->validated());

        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, int $id)
    {
        $product = $this->service->update($id, $request->validated());

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);
    }
}
