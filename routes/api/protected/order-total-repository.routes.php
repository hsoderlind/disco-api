<?php

use App\Http\Controllers\Api\OrderTotalRepositoryController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderTotalRepositoryController::class)
    ->prefix('order-total-repository')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('order_total_repositories.list');
        Route::get('/{name}', 'read')
            ->name('order_total_repositories.read')
            ->whereAlpha('name');
        Route::put('/{name}', 'update')
            ->name('order_total_repositories.update')
            ->whereAlpha('name');
    });
