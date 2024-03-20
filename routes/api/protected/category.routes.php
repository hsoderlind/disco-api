<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryController::class)
    ->prefix('category')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')->name('list');
        Route::post('/', 'create')->name('create');
        Route::get('/{id}', 'show')
            ->name('show')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('update')
            ->whereNumber('id');
        Route::delete('/{id}', 'destroy')
            ->name('destroy')
            ->whereNumber('id');
    })
    ->name('category.');
