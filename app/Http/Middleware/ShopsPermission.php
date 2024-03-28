<?php

namespace App\Http\Middleware;

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
        $shopIdFromQuery = $request->query('shop_id');
        $shopidFromParams = $request->route()->parameter('shopId');
        $shopId = $shopIdFromHeader ?? $shopIdFromQuery ?? $shopidFromParams;

        if (empty($shopId)) {
            abort(404);
        }

        ShopSession::setId($shopId);

        $shop = ShopService::get($shopId);
        $userBelongsToShop = ShopService::verifyUser($request->user(), $shop);

        if (! $userBelongsToShop) {
            abort(403);
        }

        $request->merge(['shop' => $shop]);

        return $next($request);
    }
}
