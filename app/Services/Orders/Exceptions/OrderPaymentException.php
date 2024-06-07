<?php

namespace App\Services\Orders\Exceptions;

use Exception;

class OrderPaymentException extends Exception
{
    protected int $orderId;

    protected string $paymentName;

    protected ?string $reason = null;

    public function __construct(int $orderId, string $paymentName, ?string $reason = null, string $message = 'Betalningen misslyckades')
    {
        $this->orderId = $orderId;
        $this->paymentName = $paymentName;
        $this->reason = $reason;
        parent::__construct($message);
    }

    /**
     * Get the value of paymentName
     */
    public function getPaymentName()
    {
        return $this->paymentName;
    }

    /**
     * Get the value of reason
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Get the value of orderId
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @see https://laravel.com/docs/10.x/errors#exception-log-context
     */
    public function context(): array
    {
        return [
            'order_id' => $this->orderId,
            'payment_name' => $this->paymentName,
            'reason' => $this->reason,
        ];
    }
}
