<?php

use App\Http\Controllers\Api\ProductController;
use App\Services\Product\ProductState;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)
    ->prefix('product')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/{state?}', 'index')
            ->name('list')
            ->whereIn('state', ProductState::values());
        Route::post('/', 'create')->name('create');
        Route::get('/{id}', 'show')
            ->name('show')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('update')
            ->whereNumber('id');
        Route::delete('/{id}', 'destroy')
            ->name('destroy')
            ->whereNumber('id');
    })
    ->name('product.');
