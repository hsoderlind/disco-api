<?php

use App\Discogs\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('discogs')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/authed/{shopId}', 'checkAuthed')
            ->name('discogs.auth.check')
            ->whereNumber('shopId');
    });
