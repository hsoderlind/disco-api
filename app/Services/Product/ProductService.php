<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

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

    /**
     * Get one product
     *
     * @param  array<string | array<string, function>>|null  $withRelations
     */
    public function read(int $id, ?array $withRelations = []): Product
    {
        $product = Product::findOrFail($id);

        if (count($withRelations) > 0) {
            foreach ($withRelations as $relation) {
                if (is_string($relation)) {
                    $product->load($relation);
                } elseif (is_array($relation) && count($relation) === 2 && is_callable($relation[1])) {
                    $product->load($relation[0], $relation[1]);
                } else {
                    throw new InvalidArgumentException('The array entry does not match the valid format of array<string | array<string, function>>.');
                }
            }
        }

        return $product;
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
