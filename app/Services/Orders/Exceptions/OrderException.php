<?php

namespace App\Services\Orders\Exceptions;

use Exception;

abstract class OrderException extends Exception
{
    protected int $orderId;

    public function __construct(int $orderId, string $message)
    {
        $this->orderId = $orderId;
        parent::__construct($message);
    }

    /**
     * Get the value of orderId
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    public function toArray(): array
    {
        return ['order_id' => $this->orderId];
    }

    public function context()
    {
        return ['order_id' => $this->orderId];
    }
}
