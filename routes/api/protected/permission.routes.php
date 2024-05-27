<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::controller(PermissionController::class)
    ->prefix('permission')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('permission.list');
        Route::get('/{roleId}', 'listByRole')
            ->name('permission.list_by_role')
            ->whereNumber('roleId');
        Route::put('/{roleId}', 'update')
            ->name('permission.update')
            ->whereNumber('roleId');
    });
