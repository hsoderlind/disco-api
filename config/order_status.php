<?php

use App\Services\OrderStatus\Actions\OrderConfirmationAction;

return [
    'actions' => [
        'order_confirmation' => [
            'name' => 'order_confirmation',
            'title' => 'Skicka orderbekräftelse',
            'description' => 'Skicka mejl med orderbekräftelse till kunden.',
            'handler' => OrderConfirmationAction::class,
        ],
        'order_receipt' => [
            'name' => 'order_receipt',
            'title' => 'Skicka orderkvitto',
            'description' => 'Skicka mejl med orderkvitto till kunden.',
            'handler' => '', // The action to perform on the order
        ],
        'order_cancellation' => [
            'name' => 'order_cancellation',
            'title' => 'Skicka orderavslag',
            'description' => 'Skicka mejl med information om avbruten beställning till kunden.',
            'handler' => '', // The action to perform on the order
        ],
        'order_refund' => [
            'name' => 'order_refund',
            'title' => 'Skicka återbetalning',
            'description' => 'Skicka mejl om återbetalning till kunden.',
            'handler' => '', // The action to perform on the order
        ],
        'order_status_changed' => [
            'name' => 'order_status_changed',
            'title' => 'Skicka orderpåminnelse',
            'description' => 'Skicka mejl om ändrad orderstatus till kunden',
            'handler' => '', // The action to perform on the order
        ],
        'order_payment_reminder' => [
            'name' => 'order_payment_reminder',
            'title' => 'Skicka betalningspåminnelse',
            'description' => 'Skicka mejl om betalningspåminnelse till kunden',
            'handler' => '', // The action to perform on the order
        ],
        'order_failed' => [
            'name' => 'order_failed',
            'title' => 'Markera beställningen som misslyckad',
            'description' => 'Markera beställningen som misslyckad. Obs: den orderstatus som denna åtgärd appliceras på kommer att användas i fall en beställning misslyckas. Var därför noga med vilken orderstatus du applicerar åtgärden på.',
            'handler' => '', // The action to perform on the order
        ],
    ],
];
