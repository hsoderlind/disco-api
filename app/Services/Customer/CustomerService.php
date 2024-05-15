<?php

namespace App\Services\Customer;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\AbstractService;
use App\Services\Account\AccountService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerService extends AbstractService
{
    protected $resource = CustomerResource::class;

    public function list()
    {
        $this->data = Customer::inShop($this->shopId)->get();

        return $this;
    }

    public function create(array $data)
    {
        $this->data = DB::transaction(function () use ($data) {
            /** @var \App\Models\Customer $model */
            $model = new Customer([
                'person_name' => $data['person_name'] ?? null,
                'company_name' => $data['company_name'] ?? null,
                'email' => $data['email'],
                'password' => isset($data['password']) && ! is_null($data['password']) ? Hash::make($data['password']) : null,
                'ssn' => $data['ssn'] ?? null,
                'orgno' => $data['orgno'] ?? null,
                'vatno' => $data['vatno'] ?? null,
                'taxable' => $data['taxable'],
                'currency' => $data['currency'],
                'note' => $data['note'] ?? null,
            ]);

            if (isset($data['account'])) {
                $account = AccountService::factory($this->shopId)
                    ->create($data['account'])
                    ->get();
                $model->account()->save($account);
            }

            if (isset($data['shipping_address'])) {
                $shippingAddress = AccountService::factory($this->shopId)
                    ->create($data['shipping_address'])
                    ->get();
                $model->shippingAddress()->associate($shippingAddress);
            }

            if (isset($data['billing_address'])) {
                $billingAddress = AccountService::factory($this->shopId)
                    ->create($data['billing_address'])
                    ->get();
                $model->billingAddress()->associate($billingAddress);
            }

            $model->save();

            return $model;
        });

        return $this;
    }

    public function read(int $id, ?array $withRelations = null)
    {
        if (is_null($withRelations)) {
            $this->data = Customer::inShop($this->shopId)->findOrFail($id);
        } else {
            $this->data = Customer::inShop($this->shopId)->with($withRelations)->findOrFail($id);
        }

        return $this;
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $this->read($id);
            $this->data->fill([
                'person_name' => $data['person_name'] ?? null,
                'company_name' => $data['company_name'] ?? null,
                'ssn' => $data['ssn'] ?? null,
                'orgno' => $data['orgno'] ?? null,
                'vatno' => $data['vatno'] ?? null,
                'taxable' => $data['taxable'],
                'currency' => $data['currency'],
                'note' => $data['note'] ?? null,
            ]);

            if (isset($data['email']) && $data['email'] != $this->data->email) {
                $this->data->email = $data['email'];
            }

            $this->data->save();

            if (isset($data['account'])) {
                AccountService::factory($this->shopId)->update($data['account']['id'], $data['account']);
            }

            if (isset($data['shipping_address'])) {
                AccountService::factory($this->shopId)->update($data['shipping_address']['id'], $data['shipping_address']);
            }

            if (isset($data['billing_address'])) {
                AccountService::factory($this->shopId)->update($data['billing_address']['id'], $data['billing_address']);
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $this;
    }

    public function delete(int $id)
    {
        $this->read($id);

        return $this->data->delete();
    }
}
