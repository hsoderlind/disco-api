<?php

use App\Http\Controllers\Api\AttributeTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(AttributeTypeController::class)
    ->prefix('attribute-type')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/product/{productId}', 'listByProduct')
            ->name('listByProduct')
            ->whereNumber('productId');
        Route::post('/', 'create')->name('create');
        Route::put('/{id}', 'update')
            ->name('update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('delete')
            ->whereNumber('id');
    })
    ->name('attribute_type');
