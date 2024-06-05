<?php

namespace App\Services\Orders;

use App\Exceptions\ShopContextRequiredException;
use App\Http\Resources\OrderTotalRepositoryResource;
use App\Models\OrderTotalRepository;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;

class OrderTotalRepositoryService extends AbstractService
{
    protected $resource = OrderTotalRepositoryResource::class;

    protected function boot()
    {
        if (is_null($this->shopId)) {
            throw new ShopContextRequiredException('Shop context required');
        }
    }

    public function list()
    {
        $this->data = OrderTotalRepository::inShop($this->shopId)
            ->orderBy('sort_order')
            ->orderby('title')
            ->get();

        return $this;
    }

    public function read(string $name)
    {
        $this->data = OrderTotalRepository::inShop($this->shopId)->findOrFail($name);

        return $this;
    }

    public function update(string $name, array $data)
    {
        $this->data = DB::transaction(function () use ($name, $data) {
            /** @var \App\Models\OrderTotalRepository $model */
            $model = $this->read($name)->get();

            $model->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'sort_order' => $data['sort_order'],
                'active' => $data['active'],
                'component' => $data['component'],
                'admin_component' => $data['admin_component'],
                'configuration' => $data['configuration'],
            ]);

            return $model;
        });

        return $this;
    }
}
