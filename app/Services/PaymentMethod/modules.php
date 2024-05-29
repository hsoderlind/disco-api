<?php

use App\Services\PaymentMethod\Modules\Cash;
use App\Services\PaymentMethod\Modules\Invoice;

return collect([
    (new Cash())->toArray(),
    (new Invoice())->toArray(),
])->sortBy('title');
