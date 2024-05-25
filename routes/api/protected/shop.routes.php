<?php

use App\Http\Controllers\Api\ShopController;
use Illuminate\Support\Facades\Route;

Route::controller(ShopController::class)
    ->prefix('shop')
    ->group(function () {
        Route::get('/', 'listByUser')
            ->name('shop.index');
        Route::post('/', 'create')
            ->name('shop.create');
        Route::get('/{urlAlias}', 'readByUrlAlias')
            ->name('shop.readByUrlAlias');
        Route::put('/{id}', 'update')
            ->name('shop.update')
            ->whereNumber('id');
        Route::put('/{id}/logotype/{context}', 'logotype')
            ->middleware(['shop_id'])
            ->name('shop.logotype')
            ->whereNumber('id')
            ->whereIn('context', ['default', 'mini']);
    })
    ->name('shop.');
