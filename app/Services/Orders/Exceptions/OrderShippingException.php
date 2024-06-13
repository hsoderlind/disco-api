<?php

namespace App\Services\Orders\Exceptions;

use Exception;

class OrderShippingException extends OrderException
{
    protected string $shippingName;

    protected ?string $reason = null;

    public function __construct(int $orderId, string $shippingName, ?string $reason = null, string $message = 'Leveranssättet misslyckades')
    {
        $this->shippingName = $shippingName;
        $this->reason = $reason;
        parent::__construct($orderId, $reason ?? $message);
    }

    /**
     * Get the value of shippingName
     */
    public function getShippingName()
    {
        return $this->shippingName;
    }

    /**
     * Get the value of reason
     */
    public function getReason()
    {
        return $this->reason;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'shipping_name' => $this->shippingName,
            'reason' => $this->reason,
        ]);
    }

    /**
     * @see https://laravel.com/docs/10.x/errors#exception-log-context
     */
    public function context(): array
    {
        return array_merge(parent::context(), ['shipping_name' => $this->shippingName]);
    }
}
