<?php

use App\Http\Controllers\Api\PaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::controller(PaymentMethodController::class)
    ->middleware(['shop_id'])
    ->prefix('payment-method')
    ->group(function () {
        Route::get('/modules', 'modules')
            ->name('payment_method.modules');
        Route::get('/', 'index')
            ->name('payment_method.list');
        Route::post('/{name}/install', 'install')
            ->name('payment_method.install')
            ->whereAlpha('name');
        Route::get('/{name}', 'read')
            ->name('payment_method.read')
            ->whereAlpha('name');
        Route::put('/{name}', 'update')
            ->name('payment_method.update')
            ->whereAlpha('name');
        Route::put('/{name}/core', 'updateCore')
            ->name('payment_method.update_core')
            ->whereAlpha('name');
        Route::delete('/{name}/uninstall', 'uninstall')
            ->name('payment_method.uninstall')
            ->whereAlpha('name');
    });
