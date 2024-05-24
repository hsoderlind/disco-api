<?php

use App\Helpers\Frontend;
use Illuminate\Support\Facades\Route;

if (app()->isLocal()) {
    Route::get('/mail/shop-user-invited', function () {
        return view('mail.shop-user-invited', [
            'title' => 'Inbjudan till '.config('app.name'),
            'name' => 'John Doe',
            'inviterName' => 'Henric Söderlind',
            'registerUrl' => Frontend::urlTo('/register'),
        ]);
    });
}
