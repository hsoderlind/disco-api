<?php

namespace App\Services\OrderStatus;

use App\Models\Order;
use Spatie\Activitylog\Contracts\Activity;

abstract class Action
{
    public function __construct(protected readonly array $meta)
    {
        //
    }

    public function __invoke(Order $order)
    {
        $orderAction = $order->actions()->create([
            'order_status_id' => $order->currentStatus->newStatus->getKey(),
            'action' => $this->meta['name'],
            'sort_order' => $order->currentStatus->newStatus->actions()->where('action', $this->meta['name'])->first()->sort_order,
        ]);
        $this->handle($order);
        $orderAction->complete();
    }

    abstract protected function handle(Order $order): Activity;
}
