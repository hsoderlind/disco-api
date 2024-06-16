<?php

use App\Http\Controllers\Api\ProductStockController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductStockController::class)
    ->prefix('product-stock')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('product_stock.list');
        Route::get('/{id}', 'read')
            ->name('product_stock.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('product_stock.update')
            ->whereNumber('id');
    });
