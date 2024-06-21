<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)
    ->prefix('order-status-history')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::post('/{id}', 'update')
            ->name('order_status_history.create')
            ->whereNumber('id');
    });
