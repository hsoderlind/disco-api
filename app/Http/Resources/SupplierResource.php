<?php

namespace App\Http\Resources;

use DragonCode\Support\Facades\Helpers\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return Arr::only($this->resource, ['id', 'name']);
    }
}
