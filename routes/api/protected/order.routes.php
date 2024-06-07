<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)
    ->prefix('order')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('order.list');
        Route::post('/', 'create')
            ->name('order.create');
        Route::put('/{id}', 'update')
            ->name('order.update')
            ->whereNumber('id');
    });
