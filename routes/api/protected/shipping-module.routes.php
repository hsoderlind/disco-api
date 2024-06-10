<?php

use App\Http\Controllers\Api\ShippingModulesController;
use Illuminate\Support\Facades\Route;

Route::controller(ShippingModulesController::class)
    ->prefix('shipping-module')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('shipping_modules.list');
        Route::post('/', 'install')
            ->name('shipping_modules.install');
        Route::get('/{name}', 'read')
            ->name('shipping_modules.read')
            ->whereAlpha('name');
        Route::put('/{name}', 'update')
            ->name('shipping_modules.update')
            ->whereAlpha('name');
        Route::delete('/{name}', 'uninstall')
            ->name('shipping_modules.uninstall')
            ->whereAlpha('name');
    });
