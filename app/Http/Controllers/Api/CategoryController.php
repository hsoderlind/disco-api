<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\Category\CategoryService;
use App\Services\Shop\ShopSession;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = new CategoryService(ShopSession::getId());
    }

    public function index(Request $request)
    {
        $categories = $this->service->list();

        return CategoryResource::collection($categories);
    }

    public function create(CategoryRequest $request)
    {
        $category = $this->service->create($request->validated());

        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, int $id)
    {
        $category = $this->service->update($id, $request->validated());

        return new CategoryResource($category);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if (! $deleted) {
            abort(HttpResponseCode::METHOD_NOT_ALLOWED, 'Kategorin raderades inte.');
        }

        response()->setStatusCode(HttpResponseCode::OK)->send();
    }
}
