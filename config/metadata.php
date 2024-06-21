<?php

use App\Models\Customer;
use App\Models\Order;

return [
    'providers' => [
        'customer' => Customer::class,
        'order' => Order::class,
    ],
];
