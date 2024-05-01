<?php

use App\Discogs\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('discogs')
    ->middleware(['set_shop'])
    ->group(function () {
        Route::get('/auth/{shopId}', 'initAuth')
            ->name('discogs.auth.init')
            ->whereNumber('shopId');
        Route::get('/auth/complete/{shopId}', 'completeAuth')
            ->name('discogs.auth.complete')
            ->whereNumber('shopId');
    });
