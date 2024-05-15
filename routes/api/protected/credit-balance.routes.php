<?php

use App\Http\Controllers\CreditBalanceController;
use Illuminate\Support\Facades\Route;

Route::controller(CreditBalanceController::class)
    ->prefix('credit-balance')
    ->group(function () {
        Route::get('/customer/{customer_id}', 'index')
            ->name('credit-balance.index')
            ->whereNumber('customer_id');
        Route::post('/', 'create')
            ->name('credit-balance.create');
        Route::get('/{id}', 'read')
            ->name('credit-balance.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('credit-balance.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('credit-balance.delete')
            ->whereNumber('id');
    });
