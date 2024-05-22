<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use RuntimeException;

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
        if (is_null($this->data)) {
            return null;
        } elseif ($this->data instanceof Collection) {
            return $this->resource::collection($this->data);
        } elseif ($this->data instanceof Model) {
            return new $this->resource($this->data);
        } elseif (Arr::isList($this->data)) {
            return $this->resource::collection($this->data);
        } elseif (Arr::isAssoc($this->data)) {
            return new $this->resource($this->data);
        } else {
            throw new RuntimeException(get_class($this).'::$data must be an instance of Illuminate\Database\Eloquent\Collection or Illuminate\Database\Eloquent\Model or be an array');
        }
    }
}
