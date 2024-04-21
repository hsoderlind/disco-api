<?php

use App\Http\Controllers\Api\FileController;
use Illuminate\Support\Facades\Route;

Route::controller(FileController::class)
    ->prefix('file')
    ->group(function () {
        Route::get('/download/{shopId}/{userId}/{id}', 'signedDownload')
            ->name('signed_download')
            ->whereNumber('shopId')
            ->whereNumber('userId')
            ->whereNumber('id');
    })
    ->name('file.');
