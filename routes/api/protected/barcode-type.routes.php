<?php

use App\Http\Controllers\Api\BarcodeTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(BarcodeTypeController::class)
    ->prefix('barcode-type')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')->name('list');
        Route::post('/', 'create')->name('create');
        Route::put('/{id}', 'update')
            ->name('update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('destroy')
            ->whereNumber('id');
    })
    ->name('barcode_type.');
