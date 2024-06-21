<?php

namespace App\Services\Orders;

use App\Rules\ExistsInShop;
use App\Validation\Rules;

class OrderStatusHistoryRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access order');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT';
    }

    public function getRules(): array
    {
        return [
            'order_status_id' => ['required', 'integer', new ExistsInShop('order_statuses', 'id')],
            'note' => 'nullable|string',
            'email_content' => 'nullable|string',
        ];
    }
}
