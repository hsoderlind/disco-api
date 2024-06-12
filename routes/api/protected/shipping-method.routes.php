<?php

use App\Http\Controllers\Api\ShippingMethodController;
use Illuminate\Support\Facades\Route;

Route::controller(ShippingMethodController::class)
    ->prefix('shipping-method')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('shipping_methods.list');
        Route::get('/{name}', 'read')
            ->name('shipping_methods.read')
            ->where('name', '[a-z0-9_-]+');
        Route::put('/{name}', 'update')
            ->name('shipping_methods.update')
            ->where('name', '[a-z0-9_-]+');
    });
