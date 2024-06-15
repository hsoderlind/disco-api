<?php

namespace App\Services\OrderStatus;

use App\Models\Order;
use App\Models\OrderStatusAction;
use App\Services\AbstractService;
use RuntimeException;

class OrderStatusActionService extends AbstractService
{
    protected ActionRepositoryService $actionRepositoryService;

    protected function boot()
    {
        $this->actionRepositoryService = ActionRepositoryService::factory();
    }

    public function runForOrder(Order $order)
    {
        foreach ($order->currentStatus->newStatus->actions as $action) {
            $handler = $this->resolveHandler($action);
            $handler($order);
        }
    }

    protected function resolveHandler(OrderStatusAction $action): Action
    {
        $entry = $this->actionRepositoryService->read($action->action)->get();

        if (is_null($entry)) {
            throw new RuntimeException("No order status action called `$action->action`");
        }

        return new $entry['handler']($entry);
    }
}
