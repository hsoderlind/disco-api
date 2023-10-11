<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'email_verified_at' => optional($this->email_verified_at)->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'account' => new AccountResource($this->whenLoaded('account')),
            'shops' => ShopResource::collection($this->whenLoaded('shops')),
        ];
    }
}
