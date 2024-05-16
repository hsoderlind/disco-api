<?php

namespace App\Services\CreditBalance;

use App\Models\CreditBalance;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;

class CreditBalanceService extends AbstractService
{
    public function listByCustomerId(int $customerId)
    {
        $this->data = CreditBalance::forCustomer($customerId)->orderBy('created_at', 'desc')->get();

        return $this;
    }

    public function create(array $data)
    {
        $model = $this->readByCustomerId($data['customer_id'])->get();
        $currentBalance = $model->current_balance ?? 0;

        $this->data = DB::transaction(function () use ($data, $currentBalance) {
            $model = CreditBalance::create([
                'customer_id' => $data['customer_id'],
                'current_balance' => $this->calcCurrentBalance($currentBalance, $data['adjusted_balance'], $data['adjustment_type']),
                'adjusted_balance' => $data['adjusted_balance'],
                'adjustment_type' => $data['adjustment_type'],
                'note' => $data['note'],
            ]);

            return $model;
        });

        return $this;
    }

    public function readByCustomerId(int $customerId)
    {
        $this->data = CreditBalance::forCustomer($customerId)->latest()->first();

        return $this;
    }

    public function read(int $id)
    {
        $this->data = CreditBalance::inShop($this->shopId)->findOrFail($id);

        return $this;
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $model = $this->read($id)->get();
            $model->update([
                'current_balance' => $this->calcCurrentBalance($model->current_balance, $data['adjusted_balance'], $data['adjustment_type']),
                'adjusted_balance' => $data['adjusted_balance'],
                'adjustment_type' => $data['adjustment_type'],
                'note' => $data['note'],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $this;
    }

    public function delete(int $id)
    {
        $model = $this->read($id)->get();
        $deleted = $model->delete();

        return $deleted;
    }

    protected function calcCurrentBalance(int $currentBalance, int $adjustmentBalance, string $adjustmentType): int
    {
        if ($adjustmentType == 'credit') {
            return $currentBalance + $adjustmentBalance;
        } else {
            return $currentBalance - $adjustmentBalance;
        }
    }
}
