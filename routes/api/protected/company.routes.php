<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::controller(CompanyController::class)
    ->prefix('company')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')->name('company.for_shop');
        Route::post('/', 'create')->name('company.create');
        Route::get('/{id}', 'read')
            ->name('company.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('company.update')
            ->whereNumber('id');
    });
