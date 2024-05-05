<?php

use App\Discogs\Http\Controllers\AuthController;
use App\Discogs\Http\Controllers\MarketplaceController;
use App\Discogs\Http\Controllers\MasterController;
use App\Discogs\Http\Controllers\ReleaseController;
use App\Discogs\Http\Controllers\SearchController;
use App\Discogs\Http\Controllers\WantlistController;
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
        Route::get('/master/release-versions', 'releaseVersions')
            ->name('discogs.master.release_versions');
    });

Route::controller(ReleaseController::class)
    ->prefix('discogs')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('release', 'index')->name('discogs.release');
        Route::get('release/stats', 'stats')->name('discogs.release.stats');
    });

Route::controller(WantlistController::class)
    ->prefix('discogs')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::put('/wantlist/add', 'add')->name('discogs.wantlist.add');
    });

Route::controller(MarketplaceController::class)
    ->prefix('discogs')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('marketplace/price-suggestions', 'priceSuggestions')
            ->name('discogs.marketplace.price_suggestions');
    });
