<?php

namespace App\Services\Orders\Exceptions;

use Exception;

class OrderPaymentException extends OrderException
{
    protected string $paymentName;

    protected ?string $reason = null;

    public function __construct(int $orderId, string $paymentName, ?string $reason = null, string $message = 'Betalningen misslyckades')
    {
        $this->paymentName = $paymentName;
        $this->reason = $reason;
        parent::__construct($orderId, $reason ?? $message);
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

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'payment_name' => $this->paymentName,
            'reason' => $this->reason,
        ]);
    }

    /**
     * @see https://laravel.com/docs/10.x/errors#exception-log-context
     */
    public function context(): array
    {
        return array_merge(parent::context(), ['payment_name' => $this->paymentName]);
    }
}
