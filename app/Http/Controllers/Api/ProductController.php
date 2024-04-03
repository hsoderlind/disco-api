<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Shop\ShopSession;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new ProductService(ShopSession::getId());
    }

    public function index(Request $request)
    {
        $products = $this->service->list((int) $request->query('category'));

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

    public function destroy(ProductRequest $request, int $id)
    {
        $deleted = $this->service->delete($id);

        if (! $deleted) {
            abort(HttpResponseCode::METHOD_NOT_ALLOWED, 'Produkten raderades inte.');
        }

        response()->setStatusCode(HttpResponseCode::OK)->send();
    }
}
