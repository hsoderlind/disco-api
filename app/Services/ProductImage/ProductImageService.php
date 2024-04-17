<?php

namespace App\Services\ProductImage;

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

    public function create(array $data): ProductImage
    {
        $model = new ProductImage($data);
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
        $model->update($data);

        return $model;
    }

    public function delete(int $id): bool
    {
        $model = ProductImage::inShop($this->shopId)->findOrFail($id);
        $deleted = $model->delete();

        return $deleted;
    }
}
