<?php

namespace App\Services\OrderStatus;

use App\Http\Resources\ActionRepositoryCollection;
use App\Http\Resources\ActionRepositoryResource;
use App\Services\AbstractService;
use Illuminate\Support\Collection;

class ActionRepositoryService extends AbstractService
{
    protected $resource = ActionRepositoryResource::class;

    protected $collectionResource = ActionRepositoryCollection::class;

    protected Collection $actions;

    protected function boot()
    {
        $this->actions = collect(config('order_status.actions'));
    }

    public function list()
    {
        $this->data = $this->actions->values();

        return $this;
    }

    public function read(string $name)
    {
        $this->data = $this->actions->get($name);

        return $this;
    }
}
