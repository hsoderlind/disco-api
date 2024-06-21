<?php

namespace App\Services\OrderStatus\Actions;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Services\OrderStatus\Action;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Contracts\Activity;

class OrderStatusChangedAction extends Action
{
    protected function handle(Order $order): Activity
    {
        $customContent = request()->input('mail_content');
        Mail::to($order->customer)->queue(new OrderStatusUpdated($order, $customContent));

        return activity()->performedOn($order)->log('Mejl om statusuppdatering skickades.');
    }
}
