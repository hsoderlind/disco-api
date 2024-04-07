<?php

use App\Http\Controllers\Api\AttributeValueController;
use Illuminate\Support\Facades\Route;

Route::controller(AttributeValueController::class)
    ->prefix('attribute-value')
    ->middleware(['shop_id'])
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/attribute-type/{attributeTypeId}', 'listByAttributeType')
            ->name('listByAttributeType')
            ->whereNumber('attributeTypeId');
        Route::post('/', 'create')->name('create');
        Route::get('/{id}', 'read')
            ->name('read')
            ->whereNumber('id');
        Route::put('/{id}', 'update')
            ->name('update')
            ->whereNumber('id');
        Route::delete('/{id}', 'delete')
            ->name('delete')
            ->whereNumber('id');
    })
    ->name('attribute_value');
