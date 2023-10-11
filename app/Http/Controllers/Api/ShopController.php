<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use App\Services\Shop\ShopService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function create(ShopRequest $request)
    {
        $user = $request->user();
        $shop = ShopService::create($request->validated(), $user);

        return new ShopResource($shop);
    }

    public function readByUrlAlias(Request $request, string $urlAlias)
    {
        $shop = ShopService::getByUrlAlias($urlAlias);

        return new ShopResource($shop);
    }

    public function listByUser(Request $request)
    {
        $user = $request->user();
        $shops = ShopService::listByUser($user);

        return ShopResource::collection($shops);
    }
}
