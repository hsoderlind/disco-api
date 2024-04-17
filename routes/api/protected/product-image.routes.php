<?php

use App\Http\Controllers\Api\ProductImageController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductImageController::class)
    ->prefix('product-image')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/list/{productId}', 'index')
            ->whereNumber('productId')
            ->name('list');
        Route::post('/', 'create')->name('create');
        Route::get('/{id}', ' read')
            ->whereNumber('id')
            ->name('read');
        Route::put('/{id}', 'update')
            ->whereNumber('id')
            ->name('update');
        Route::delete('/{id}')
            ->whereNumber('id')
            ->name('delete');
    })
    ->name('product_image.');
