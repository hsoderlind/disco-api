<?php

use Illuminate\Support\Facades\Route;

if (app()->isLocal()) {
    Route::get('/mail/shop-user-invited', function () {
        return view('mail.shop-user-removed', [
            'title' => 'Borttagen från Vinylkällaren',
            'name' => 'John Doe',
            'shopName' => 'Vinylkällaren',
        ]);
    });
}
