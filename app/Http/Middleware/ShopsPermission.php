<?php

namespace App\Http\Middleware;

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

        if (! empty($shopId)) {
            // session value set on login
            setPermissionsTeamId($shopId);
        }

        return $next($request);
    }
}
