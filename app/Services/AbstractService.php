<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use JsonSerializable;
use RuntimeException;

abstract class AbstractService
{
    protected mixed $data;

    protected $resource = null;

    protected $collectionResource = null;

    private function __construct(protected readonly ?int $shopId = null)
    {
        $this->boot();
    }

    protected function boot()
    {
        //
    }

    public static function factory(?int $shopId = null)
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
        } elseif ($this->data instanceof EloquentCollection) {
            return ! is_null($this->collectionResource) && $this->collectionResource instanceof ResourceCollection ? new $this->collectionResource($this->data) : $this->resource::collection($this->data);
        } elseif ($this->data instanceof Model) {
            return new $this->resource($this->data);
        } elseif ($this->data instanceof Collection) {
            return ! is_null($this->collectionResource) && $this->collectionResource instanceof ResourceCollection ? new $this->collectionResource($this->data) : $this->resource::collection($this->data);
        } elseif ($this->data instanceof JsonSerializable) {
            $json = $this->data->jsonSerialize();
            if (is_array($json) && Arr::isList($json)) {
                return ! is_null($this->collectionResource) && $this->collectionResource instanceof ResourceCollection ? new $this->collectionResource($json) : $this->resource::collection($json);
            } elseif (is_array($json) && Arr::isAssoc($json)) {
                return new $this->resource($json);
            }
        } elseif (is_array($this->data) && Arr::isList($this->data)) {
            return ! is_null($this->collectionResource) && $this->collectionResource instanceof ResourceCollection ? new $this->collectionResource($this->data) : $this->resource::collection($this->data);
        } elseif (is_array($this->data) && Arr::isAssoc($this->data)) {
            return new $this->resource($this->data);
        } else {
            throw new RuntimeException(get_class($this).'::$data is not a valid return type.');
        }
    }
}
