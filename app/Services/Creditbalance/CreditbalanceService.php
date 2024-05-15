<?php

namespace App\Services\CreditBalance;

use App\Models\CreditBalance;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;

class CreditBalanceService extends AbstractService
{
    public function create(array $data)
    {
        $this->data = DB::transaction(function () use ($data) {
            $model = CreditBalance::create([
                'customer_id' => $data['customer_id'],
                'current_balance' => $this->calcCurrentBalance($data['adjusted_balance'], $data['adjustment_type']),
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
        $this->data = CreditBalance::forCustomer($customerId)->latest()->get();

        return $this;
    }

    public function read(int $id)
    {
        $this->data = CreditBalance::findOrFail($id);

        return $this;
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $model = $this->read($id)->get();
            $model->update([
                'current_balance' => $this->calcCurrentBalance($data['adjusted_balance'], $data['adjustment_type']),
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

    protected function calcCurrentBalance(int $adjustmentBalance, int $adjustmentType): int
    {
        if ($adjustmentType == AdjustmentType::Debet) {
            return $this->data?->current_balance ?? 0 + $adjustmentBalance;
        } else {
            return $this->data?->current_balance ?? 0 - $adjustmentBalance;
        }
    }
}
