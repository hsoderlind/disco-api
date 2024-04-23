<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractService
{
    protected static $instance = null;

    protected mixed $data;

    protected string $resource;

    private function __construct(protected readonly int $shopId)
    {
        //
    }

    public static function factory(int $shopId)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($shopId);
        }

        return self::$instance;
    }

    public function getData()
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
