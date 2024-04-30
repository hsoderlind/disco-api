<?php

namespace App\Http\Middleware;

use App\Services\Shop\ShopService;
use App\Services\Shop\ShopSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetShop
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

        if (empty($shopId)) {
            return $next($request);
        }

        ShopSession::setId($shopId);
        $shop = ShopService::get($shopId);

        $request->merge(['shop' => $shop]);

        return $next($request);
    }
}
