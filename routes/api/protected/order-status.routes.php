<?php

use App\Http\Controllers\Api\OrderStatusController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderStatusController::class)
    ->prefix('order-status')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('order_status.list');
        Route::post('/', 'create')
            ->name('order_status.create');
        Route::get('/{id}', 'read')
            ->name('order_status.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('order_status.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('order_status.delete')
            ->whereNumber('id');
    });
