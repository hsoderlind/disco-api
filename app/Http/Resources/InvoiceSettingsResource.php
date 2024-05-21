<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceSettingsResource extends JsonResource
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
            'show_orgnumber' => $this->show_orgnumber,
            'show_company_name' => $this->show_company_name,
            'show_shop_name' => $this->show_shop_name,
            'show_company_official_website' => $this->show_company_official_website,
            'show_shop_official_website' => $this->show_shop_official_website,
            'show_company_support_url' => $this->show_company_support_url,
            'show_shop_support_url' => $this->show_shop_support_url,
            'show_company_terms_of_agreement_url' => $this->show_company_terms_of_agreement_url,
            'show_shop_terms_of_agreement_url' => $this->show_shop_terms_of_agreement_url,
            'show_company_privacy_police_url' => $this->show_company_privacy_police_url,
            'show_shop_privacy_police_url' => $this->show_shop_privacy_police_url,
            'show_company_support_phone' => $this->show_company_support_phone,
            'show_shop_support_phone' => $this->show_shop_support_phone,
            'show_company_support_email' => $this->show_company_support_email,
            'show_shop_support_email' => $this->show_shop_support_email,
            'show_support_address' => $this->show_support_address,
            'show_shop_address' => $this->show_shop_address,
            'logotype' => new LogotypeResource($this->whenLoaded('logotype')),
        ];
    }
}
