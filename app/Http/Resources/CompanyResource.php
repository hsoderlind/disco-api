<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'orgnumber' => $this->orgnumber,
            'official_website' => $this->official_website,
            'support_website' => $this->support_website,
            'terms_of_agreement_url' => $this->terms_of_agreement_url,
            'privacy_police_url' => $this->privacy_police_url,
            'support_phone' => $this->support_phone,
            'support_email' => $this->support_email,
            'support_address' => new AccountResource($this->whenLoaded('supportAddress')),
        ];
    }
}
