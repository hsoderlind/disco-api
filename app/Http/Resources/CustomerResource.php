<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'person_name' => $this->person_name,
            'company_name' => $this->company_name,
            'name' => $this->name,
            'email' => $this->email,
            'ssn' => $this->ssn,
            'orgno' => $this->orgno,
            'vatno' => $this->vatno,
            'taxable' => $this->taxable,
            'currency' => $this->currency,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'account' => new AccountResource($this->whenLoaded('account')),
            'shipping_address' => new AccountResource($this->whenLoaded('shippingAddress')),
            'billing_address' => new AccountResource($this->whenLoaded('billingAddress')),
            'credit_balance' => new CreditBalanceResource($this->whenLoaded('creditBalance')),
        ];
    }
}
