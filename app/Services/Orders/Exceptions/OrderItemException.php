<?php

namespace App\Services\Orders\Exceptions;

class OrderItemException extends OrderException
{
    protected $productId;

    public function __construct(int $orderId, int $productId, string $message)
    {
        $this->productId = $productId;
        parent::__construct($orderId, $message);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), ['product_id' => $this->productId]);
    }

    public function context()
    {
        return array_merge(parent::context(), ['product_id' => $this->productId]);
    }
}
