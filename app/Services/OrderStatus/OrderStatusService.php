<?php

namespace App\Services\OrderStatus;

use App\Http\Resources\OrderStatusResource;
use App\Models\OrderStatus;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;

class OrderStatusService extends AbstractService
{
    protected $resource = OrderStatusResource::class;

    public function list()
    {
        $this->data = OrderStatus::inShop($this->shopId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this;
    }

    public function create(array $data)
    {
        $this->data = DB::transaction(function () use ($data) {
            $orderStatus = OrderStatus::create($data);

            $count = OrderStatus::inShop($this->shopId)->count();

            if ($count === 1) {
                $orderStatus->update(['is_default' => true]);
            }

            return $orderStatus;
        });

        return $this;
    }

    public function read(int $id)
    {
        $this->data = OrderStatus::inShop($this->shopId)->findOrFail($id);

        return $this;
    }

    public function readDefault()
    {
        $this->data = OrderStatus::inShop($this->shopId)->isDefault()->firstOrFail();

        return $this;
    }

    public function update(int $id, array $data)
    {
        $this->data = DB::transaction(function () use ($id, $data) {
            $model = $this->read($id)->get();
            $model->update($data);

            if (isset($data['is_default'])) {
                $prevDefault = OrderStatus::inShop($this->shopId)
                    ->where('is_default')
                    ->where('id', '!=', $id)
                    ->first();

                if (! is_null($prevDefault)) {
                    $prevDefault->update(['is_default' => false]);
                }
            }

            return $model;
        });

        return $this;
    }

    public function delete(int $id)
    {
        $model = $this->read($id)->get();
        $deleted = $model->delete();

        return $deleted;
    }
}
