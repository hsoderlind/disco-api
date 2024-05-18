<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

Route::controller(CustomerController::class)
    ->prefix('customer')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')->name('customer.list');
        Route::post('/', 'create')->name('customer.create');
        Route::get('/{id}', 'read')
            ->name('customer.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('customer.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('customer.delete')
            ->whereNumber('id');
    });
