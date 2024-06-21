<?php

use App\Mail\OrderConfirmation;
use App\Mail\OrderReceipt;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Services\Shop\ShopSession;
use Illuminate\Support\Facades\Route;

if (app()->isLocal()) {
    Route::get('/mail/shop-user-removed', function () {
        return view('mail.shop-user-removed', [
            'title' => 'Borttagen från Vinylkällaren',
            'name' => 'John Doe',
            'shopName' => 'Vinylkällaren',
        ]);
    });

    Route::get('/mail/product-out-of-stock', function () {
        return view('mail.product-out-of-stock', [
            'title' => 'Testprodukten är slutsåld',
            'name' => 'John Doe',
            'productName' => 'Testprodukten',
            'requestedQty' => 10,
            'shopName' => 'Vinylkällaren',
        ]);
    });

    Route::get('/mail/order-receipt', function () {
        ShopSession::setId(1);
        $order = Order::inShop(ShopSession::getId())->latest()->first();

        return (new OrderReceipt($order))->render();
    });

    Route::get('/mail/order-confirmation', function () {
        ShopSession::setId(1);
        $order = Order::inShop(ShopSession::getId())->latest()->first();

        return (new OrderConfirmation($order))->render();
    });

    Route::get('/mail/order-status-updated', function () {
        ShopSession::setId(1);
        $order = Order::inShop(ShopSession::getId())->latest()->first();

        return (new OrderStatusUpdated($order))->render();
    });
}
