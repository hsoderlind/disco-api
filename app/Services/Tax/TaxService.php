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
        $tax = new Tax($data);
        $tax->shop_id = $this->shopId;
        $tax->save();

        return $tax;
    }

    public function update(int $id, array $data): Tax
    {
        $tax = Tax::findOrFail($id);
        $tax->fille($data);
        $tax->save();

        return $tax;
    }

    public function delete(int $id)
    {
        $tax = Tax::findOrFail($id);
        $tax->delete();
    }
}
