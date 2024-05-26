<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)
    ->prefix('user')
    ->group(function () {
        Route::get('/', 'index')->name('user.index');
        Route::post('/{id}/masquerade', 'masquerade')
            ->middleware(['shop_id'])
            ->whereNumber('id')
            ->name('user.masquerade');
        Route::post('/{id}/unmasquerade', 'unmasquerade')
            ->middleware(['shop_id'])
            ->whereNumber('id')
            ->name('user.unmasquerade');
    });
