<?php

use App\Discogs\Http\Controllers\AuthController;
use App\Discogs\Http\Controllers\MasterController;
use App\Discogs\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('discogs')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/authed/{shopId}', 'checkAuthed')
            ->name('discogs.auth.check')
            ->whereNumber('shopId');
    });

Route::controller(SearchController::class)
    ->prefix('discogs')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/search', 'index')
            ->name('discogs.search');
    });

Route::controller(MasterController::class)
    ->prefix('discogs')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/master', 'index')->name('discogs.master');
    });
