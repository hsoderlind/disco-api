<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserNotVerifiedException;
use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\LogotypeRequest;
use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use App\Services\Shop\ShopService;

class ShopController extends Controller
{
    public function create(ShopRequest $request)
    {
        $user = $request->user();
        $shop = ShopService::create($request->validated(), $user);

        return new ShopResource($shop);
    }

    public function read(ShopRequest $request, int $id)
    {
        $shop = ShopService::get($id);
        $user = $request->user();
        $verified = ShopService::verifyUser($user, $shop);

        if (! $verified) {
            throw UserNotVerifiedException::named($user->name);
        }

        $shop->load(['defaultLogotype', 'miniLogotype']);

        return new ShopResource($shop);
    }

    public function readByUrlAlias(ShopRequest $request, string $urlAlias)
    {
        $shop = ShopService::getByUrlAlias($urlAlias);
        $userBelongsToShop = ShopService::verifyUser($request->user(), $shop);

        abort_if(! $userBelongsToShop, HttpResponseCode::FORBIDDEN);

        return new ShopResource($shop);
    }

    public function listByUser(ShopRequest $request)
    {
        $user = $request->user();
        $shops = ShopService::listByUser($user);

        return ShopResource::collection($shops);
    }

    public function update(ShopRequest $request, int $id)
    {
        $shop = ShopService::update($id, $request->validated());

        return new ShopResource($shop);
    }

    public function logotype(LogotypeRequest $request, int $id, string $context)
    {
        $shop = ShopService::setLogotype($id, $context, $request->validated());

        return new ShopResource($shop);
    }
}
