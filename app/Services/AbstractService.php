<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractService
{
    protected mixed $data;

    protected $resource;

    private function __construct(protected readonly int $shopId)
    {
        //
    }

    public static function factory(int $shopId)
    {
        return new static($shopId);
    }

    public function &get()
    {
        return $this->data;
    }

    public function toResource(): JsonResource
    {
        if (isset($this->data[0])) {
            return $this->resource::collection($this->data);
        } else {
            return new $this->resource($this->data);
        }
    }
}
