<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    foreach (glob(__DIR__.'/api/protected/*.routes.php') as $file) {
        require $file;
    }
});

foreach (glob(__DIR__.'/api/public/*.routes.php') as $file) {
    require $file;
}
