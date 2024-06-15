<?php

use App\Http\Controllers\Api\ActionRepositoryController;
use Illuminate\Support\Facades\Route;

Route::controller(ActionRepositoryController::class)
    ->prefix('action-repository')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('action_repository.list');
        Route::get('/{name}', 'read')
            ->name('action_repository.read')
            ->where('name', '[a-z0-9_-]+');
    });
