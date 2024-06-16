<?php

use App\Http\Controllers\Api\FileController;
use Illuminate\Support\Facades\Route;

Route::controller(FileController::class)
    ->prefix('file')
    ->group(function () {
        Route::get('/download/{shopId}/{userId}/{id}', 'signedDownload')
            ->name('file.signed_download')
            ->whereNumber('shopId')
            ->whereNumber('userId')
            ->whereNumber('id');
        Route::get('/{shopId}/{id}', 'publicDownload')
            ->name('file.web.download')
            ->whereNumber('shopId')
            ->whereNumber('id')
            ->middleware(['set_shop']);
    });
