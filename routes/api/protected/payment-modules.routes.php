<?php

use App\Http\Controllers\Api\PaymentModulesController;
use Illuminate\Support\Facades\Route;

Route::controller(PaymentModulesController::class)
    ->prefix('payment-method/modules')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('payment_modules.list');
    });
