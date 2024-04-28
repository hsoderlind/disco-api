<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractService
{
    protected static $instance = null;

    protected mixed $data;

    protected $resource;

    private function __construct(protected readonly int $shopId)
    {
        //
    }

    public static function factory(int $shopId)
    {
        if (is_null(self::$instance)) {
            static::$instance = new static($shopId);
        }

        return static::$instance;
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
