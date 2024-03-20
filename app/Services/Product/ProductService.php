<?php

namespace App\Services\Product;

use App\Models\Product;
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

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create($data);
            $product->categories()->sync($data['categories']);
            $product->barcodes()->sync($data['barcodes']);

            return $product;
        });
    }

    public function update(int $id, array $data): Product
    {
        return DB::transaction(function () use ($id, $data) {
            $product = Product::find($id);
            $product->fill($data);
            $product->save();
            $product->categories()->sync($data['categories']);
            $product->barcodes()->sync($data['barcodes']);

            return $product;
        });
    }

    public function delete(Product $product)
    {
        $product->delete();
    }
}
