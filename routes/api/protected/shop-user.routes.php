<?php

use App\Http\Controllers\Api\ShopUserController;
use Illuminate\Support\Facades\Route;

Route::controller(ShopUserController::class)
    ->prefix('shop-user')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')->name('shop_user.list');
        Route::post('/', 'create')->name('shop_user.create');
        Route::get('/{id}', 'read')
            ->name('shop_user.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('shop_user.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('shop_user.delete')
            ->whereNumber('id');
    });
