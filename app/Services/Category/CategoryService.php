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
        $category = Category::create($data);

        return $category;
    }

    public function read(int $id): Category
    {
        return Category::inShop($this->shopId)->findOrFail($id);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->read($id);
        $category->update($data);

        return $category;
    }

    public function delete(int $id): bool
    {
        $category = Category::findOrFail($id);

        return $category->delete();
    }

    public function getAllChildrenOf(int $id, bool $includeParent = true)
    {
        $category = $this->read($id);

        return $category->getAllChildren(true);
    }
}
