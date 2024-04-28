<?php

namespace App\Services\ProductImage;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\File\FileService;

class ProductImageService
{
    public function __construct(protected readonly int $shopId)
    {

    }

    public function list(int $productId)
    {
        return ProductImage::inShop($this->shopId)
            ->whereHas('product', fn ($query) => $query->where('products.id', $productId))
            ->with('meta')
            ->get();
    }

    public function create(Product $product, array $data): ProductImage
    {
        $model = new ProductImage(
            collect($data)
                ->only(['sort_order', 'use_as_cover'])
                ->toArray()
        );
        $product->images()->save($model);
        $model->meta()->save(FileService::staticGet($this->shopId, $data['meta']['id']));
        $model->save();

        return $model;
    }

    public function get(int $id): ProductImage
    {
        return ProductImage::inShop($this->shopId)->with('meta')->findOrFail($id);
    }

    public function update(int $id, array $data): ProductImage
    {
        $model = $this->get($id);
        $model->update([
            'use_as_cover' => $data['use_as_cover'],
            'sort_order' => $data['sort_order'],
        ]);

        return $model;
    }

    public function updateOrCreate(Product $product, array $data): ProductImage
    {
        if (isset($data['id'])) {
            return $this->update($data['id'], $data);
        } else {
            return $this->create($product, $data);
        }
    }

    public function delete(int $id): bool
    {
        $model = ProductImage::inShop($this->shopId)->findOrFail($id);
        $deleted = $model->delete();

        return $deleted;
    }
}
