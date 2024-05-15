<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditBalanceHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'current_balance' => $this->current_balance,
            'adjustment_type' => $this->adjustment_type,
            'adjustment_balance' => $this->adjusted_balance,
            'note' => $this->note,
            'created_at' => $this->created_at,
        ];
    }
}
