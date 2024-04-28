<?php

namespace App\Services\ProductFile;

use App\Models\File;
use App\Models\Product;
use App\Models\ProductFile;
use App\Services\AbstractService;

class ProductFileService extends AbstractService
{
    public function create(Product $product, array $data)
    {
        $this->data = new ProductFile(
            collect($data)
                ->only(['sort_order'])
                ->toArray()
        );

        $product->files()->save($this->data);

        $this->data->meta()->save(File::inShop($this->shopId)->findOrFail($data['meta']['id']));

        $this->data->save();

        return $this;
    }

    public function read(int $id)
    {
        $this->data = ProductFile::inShop($this->shopId)->findOrFail($id);

        return $this;
    }

    public function update(int $id, array $data)
    {
        $model = &$this->read($id)->get();
        $model->sort_order = $data['sort_order'];
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
