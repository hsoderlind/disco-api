<?php

namespace App\Services\OrderStatus\Actions;

use App\Mail\OrderReceipt;
use App\Models\Order;
use App\Services\OrderStatus\Action;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Contracts\Activity;

class OrderReceiptAction extends Action
{
    protected function handle(Order $order): Activity
    {
        Mail::to($order->customer)->queue(new OrderReceipt($order));

        return activity()->performedOn($order)->log('Kvitto skickades med e-post');
    }
}
