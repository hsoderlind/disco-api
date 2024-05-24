<?php

use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

Route::controller(RoleController::class)
    ->prefix('role')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('role.list');
        Route::post('/', 'create')
            ->name('role.create');
        Route::get('/{id}', 'read')
            ->name('role.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('role.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('role.delete')
            ->whereNumber('id');
    });
