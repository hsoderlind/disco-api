<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\ProductAttribute\ProductAttributeService;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(private readonly int $shopId)
    {
        //
    }

    public function list(?int $category = 0)
    {
        if (! isset($category) || $category === 0) {
            return Product::inShop($this->shopId)->get();
        } else {
            return Product::inShop($this->shopId)
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $category))
                ->get();
        }
    }

    public function listPublished(?int $category = 0)
    {
        if (! isset($category) || $category === 0) {
            return Product::inShop($this->shopId)->isPublished()->get();
        } else {
            return Product::inShop($this->shopId)
                ->isPublished()
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $category))
                ->get();
        }
    }

    public function listDraft(?int $category = 0)
    {
        if (! isset($category) || $category === 0) {
            return Product::inShop($this->shopId)->isDraft()->get();
        } else {
            return Product::inShop($this->shopId)
                ->isDraft()
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $category))
                ->get();
        }
    }

    /**
     * Get one product
     *
     * @param  array<string | array<string, function>>|null  $withRelations
     */
    public function read(int $id, ?array $withRelations = []): Product
    {
        $product = Product::inShop($this->shopId)->findOrFail($id);

        if (count($withRelations) > 0) {
            $product->load($withRelations);
        }

        return $product;
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create($data);
            $product->categories()->sync($data['categories']);
            $product->barcodes()->sync($data['barcodes']);

            if (isset($data['product_attributes'])) {
                $productAttributeService = new ProductAttributeService($this->shopId);

                foreach ($data['product_attributes'] as $productAttributeData) {
                    $productAttribute = $productAttributeService->create($productAttributeData);

                    $productAttribute->product()->associate($product);
                }
            }

            return $product;
        });
    }

    public function update(int $id, array $data): Product
    {
        return DB::transaction(function () use ($id, $data) {
            $product = Product::inShop($this->shopId)->findOrFail($id);
            $product->update($data);
            $product->categories()->sync($data['categories']);
            $product->barcodes()->sync($data['barcodes']);

            return $product;
        });
    }

    public function delete(int $id): bool
    {
        $product = Product::inShop($this->shopId)->findOrFail($id);

        return $product->delete();
    }
}
