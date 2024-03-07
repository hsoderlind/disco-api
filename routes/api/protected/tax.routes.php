<?php

use App\Http\Controllers\Api\TaxController;
use Illuminate\Support\Facades\Route;

Route::controller(TaxController::class)
    ->prefix('tax')
    ->middleware('shop_id')
    ->group(function () {
        Route::get('/', 'index')->name('list');
        Route::post('/', 'create')->name('create');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    })
    ->name('tax.');
