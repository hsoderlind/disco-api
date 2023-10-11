<?php

use App\Http\Controllers\Api\Handheld\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('handheld')->group(function () {
    Route::post('login', 'login')->name('login');
})->name('handheld.');
