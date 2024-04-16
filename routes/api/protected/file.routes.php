<?php

use App\Http\Controllers\Api\FileController;
use Illuminate\Support\Facades\Route;

Route::controller(FileController::class)
    ->prefix('file')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/{id}', 'download')
            ->name('download')
            ->whereNumber('id');
        Route::post('/', 'upload')->name('upload');
        Route::delete('/{id}', 'delete')
            ->name('delete')
            ->whereNumber('id');
    })
    ->name('file');
