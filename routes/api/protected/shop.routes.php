<?php

use App\Http\Controllers\Api\ShopController;
use Illuminate\Support\Facades\Route;

Route::controller(ShopController::class)->prefix('shop')->group(function () {
    Route::get('/', 'listByUser')->name('index');
    Route::post('/', 'create')->name('create');
    Route::get('/{urlAlias}', 'readByUrlAlias')->name('readByUrlAlias');
    Route::put('/{id}', 'update')->name('shop.update')->whereNumber('id');
})->name('shop.');
