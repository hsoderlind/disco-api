<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent' => $this->parent,
            'level' => $this->level,
            'sort_order' => $this->sort_order,
            'children_count' => $this->whenCounted('children'),
            'children' => self::collection($this->whenLoaded('children')),
        ];
    }
}
