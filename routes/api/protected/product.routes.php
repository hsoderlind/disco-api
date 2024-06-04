<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)
    ->prefix('product')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('product.list');
        Route::get('/summary', 'listAsSummary')
            ->name('product.list_as_summary');
        Route::post('/', 'create')
            ->name('product.create');
        Route::get('/{id}', 'show')
            ->name('product.show')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('product.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'destroy')
            ->name('product.destroy')
            ->whereNumber('id');
    });
