<?php

namespace App\Services\Category;

use App\Models\Category;

class CategoryService
{
    public function __construct(private readonly int $shopId)
    {
    }

    public function list()
    {
        return Category::inShop($this->shopId)
            ->withCount('children')
            ->orderBy('parent')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): Category
    {
        $category = new Category($data);
        $category->shop_id = $this->shopId;
        $category->save();

        return $category;
    }

    public function update(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function delete(int $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }
}
