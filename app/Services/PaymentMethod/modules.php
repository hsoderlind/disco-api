<?php

use App\Services\PaymentMethod\Modules\Cash;

return collect([
    (new Cash())->toArray(),
]);
