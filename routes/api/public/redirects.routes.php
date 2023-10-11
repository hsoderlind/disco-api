<?php

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

Route::get('reset-password', function (Request $request) {
    return redirect(config('app.frontend_url').'/reset-password?'.Arr::query($request->query()));
})->name('password.reset');
