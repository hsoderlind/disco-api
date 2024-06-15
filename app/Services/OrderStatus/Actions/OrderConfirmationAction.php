<?php

namespace App\Services\OrderStatus\Actions;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Services\OrderStatus\Action;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Contracts\Activity;

class OrderConfirmationAction extends Action
{
    protected function handle(Order $order): Activity
    {
        Mail::to($order->customer)->queue(new OrderConfirmation($order));

        return activity()->performedOn($order)->log('Sent confirmation mail.');
    }
}
