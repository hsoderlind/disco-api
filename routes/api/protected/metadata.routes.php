<?php

use App\Http\Controllers\Api\MetadataController;
use Illuminate\Support\Facades\Route;

Route::controller(MetadataController::class)
    ->prefix('metadata')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/{resource}/{resourceId}', 'index')
            ->name('metadata.list')
            ->whereAlpha('resource')
            ->whereNumber('resourceId');
        Route::post('/{resource}/{resourceId}', 'create')
            ->name('metadata.create')
            ->whereAlpha('resource')
            ->whereNumber('resourceId');
        Route::get('/{id}', 'read')
            ->name('metadata.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('metadata.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('metadata.delete')
            ->whereNumber('id');
    });
