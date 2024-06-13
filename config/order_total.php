<?php

use App\Services\Orders\Modules\Shipping;
use App\Services\Orders\Modules\Subtotal;
use App\Services\Orders\Modules\Tax;
use App\Services\Orders\Modules\Total;

return [
    'modules' => [
        Subtotal::class,
        Tax::class,
        Total::class,
        Shipping::class,
    ],
];
