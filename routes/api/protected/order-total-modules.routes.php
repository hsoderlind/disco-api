<?php

use App\Http\Controllers\Api\OrderTotalModulesController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderTotalModulesController::class)
    ->prefix('order-total-module')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('order_total_modules.list');
        Route::post('/', 'install')
            ->name('order_total_modules.install');
        Route::get('/{name}', 'read')
            ->name('order_total_modules.read')
            ->whereAlpha('name');
        Route::put('/{name}', 'update')
            ->name('order_total_modules.update')
            ->whereAlpha('name');
        Route::delete('/{name}', 'uninstall')
            ->name('order_total_modules.uninstall')
            ->whereAlpha('name');
    });
