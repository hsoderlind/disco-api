<?php

namespace App\Http\Middleware;

use App\Http\Helpers\HttpResponseCode;
use App\Services\Shop\ShopService;
use App\Services\Shop\ShopSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shopIdFromHeader = $request->header('x-shop-id');
        $shopIdFromQuery = $request->query('shop_id') ?? $request->query('shopId');
        $shopIdFromParams = $request->route()->parameter('shopId');
        $shopId = $shopIdFromHeader ?? $shopIdFromQuery ?? $shopIdFromParams;

        abort_if(empty($shopId), HttpResponseCode::FORBIDDEN, 'Saknar butikskontext');

        ShopSession::setId($shopId);

        $shop = ShopService::get($shopId);
        $userBelongsToShop = ShopService::verifyUser($request->user(), $shop);

        abort_if(! $userBelongsToShop, HttpResponseCode::FORBIDDEN, 'Ajabaja, du är inte användare av butiken.');

        $request->merge(['shop' => $shop]);

        return $next($request);
    }
}
