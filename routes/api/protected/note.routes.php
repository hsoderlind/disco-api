<?php

use App\Http\Controllers\Api\NoteController;
use Illuminate\Support\Facades\Route;

Route::controller(NoteController::class)
    ->prefix('note')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/{resource}/{id}', 'index')
            ->name('note.list')
            ->whereAlpha('resource')
            ->whereNumber('id');
        Route::post('/{resource}/{id}', 'create')
            ->name('note.list')
            ->whereAlpha('resource')
            ->whereNumber('id');
        Route::get('/{id}', 'read')
            ->name('note.read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('note.update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('note.delete')
            ->whereNumber('id');
    });
