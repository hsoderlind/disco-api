<?php

namespace App\Services\ProductSpecialPrice;

use App\Http\Resources\ProductSpecialPriceResource;
use App\Models\Product;
use App\Models\ProductSpecialPrice;
use App\Services\AbstractService;

class ProductSpecialPriceService extends AbstractService
{
    protected $resource = ProductSpecialPriceResource::class;

    public function create(Product $product, array $data)
    {
        $model = new ProductSpecialPrice(['special_price' => $data['special_price']]);
        $model->entry_date = $data['entry_date'];
        $model->expiration_date = $data['expiration_date'];

        $product->specialPrices()->save($model);

        $model->save();

        $this->data = $model;

        return $this;
    }

    public function read(int $id)
    {
        $this->data = ProductSpecialPrice::inShop($this->shopId)->findOrFail($id);

        return $this;
    }

    public function update(int $id, array $data)
    {
        /** @var \App\Models\ProductSpecialPrice $model */
        $model = &$this->read($id)->get();
        $model->special_price = $data['special_price'];
        $model->entry_date = $data['entry_date'];
        $model->expiration_date = $data['expiration_date'];

        $model->save();

        return $this;
    }

    public function updateOrCreate(Product $product, array $data)
    {
        if (isset($data['id'])) {
            $this->update($data['id'], $data);
        } else {
            $this->create($product, $data);
        }
    }

    public function delete(int $id)
    {
        $model = &$this->read($id)->get();

        $deleted = $model->delete();

        return $deleted;
    }
}
