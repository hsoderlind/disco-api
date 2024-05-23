<?php

use App\Http\Controllers\Api\InvoiceSettingsController;
use Illuminate\Support\Facades\Route;

Route::controller(InvoiceSettingsController::class)
    ->prefix('invoice-settings')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'index')->name('invoice_settings.read');
        Route::put('/', 'update')->name('invoice_settings.update');
        Route::put('/logotype', 'logotype')->name('invoice_settings.logotype');
    });
