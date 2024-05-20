<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            'url_alias' => $this->url_alias,
            'address_street1' => $this->address_street1,
            'address_street2' => $this->address_street2,
            'address_zip' => $this->address_zip,
            'address_city' => $this->address_city,
            'account_owner' => $this->account_owner,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'default_logotype' => new LogotypeResource($this->whenLoaded('defaultLogotype')),
            'mini_logotype' => new LogotypeResource($this->whenLoaded('miniLogotype')),
        ];
    }
}
