<?php

namespace App\Services\Tax;

use App\Models\Tax;

class TaxService
{
    public function __construct(private readonly int $shopId)
    {

    }

    public function list()
    {
        return Tax::inShop($this->shopId)->where('active', true)->orderBy('priority')->get();
    }

    public function listWithInactive()
    {
        return Tax::inShop($this->shopId)->orderBy('priority')->get();
    }

    public function create(array $data): Tax
    {
        $tax = Tax::create($data);

        return $tax;
    }

    public function update(int $id, array $data): Tax
    {
        $tax = Tax::inShop($this->shopId)->findOrFail($id);
        $tax->update($data);

        return $tax;
    }

    public function delete(int $id): bool
    {
        $tax = Tax::inShop($this->shopId)->findOrFail($id);

        return $tax->delete();
    }
}
