<?php

namespace App\Services\Company;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Shop;
use App\Services\AbstractService;
use App\Services\Account\AccountService;
use Illuminate\Support\Facades\DB;

class CompanyService extends AbstractService
{
    protected $resource = CompanyResource::class;

    public function read(?int $id = null)
    {
        if (is_null($id)) {
            /** @var \App\Models\Shop $shop */
            $shop = Shop::find($this->shopId);
            $model = $shop->company;

            if (is_null($model)) {
                $this->create([
                    'name' => $shop->name,
                    'orgnumber' => $shop->orgnumber,
                ]);

                $this->data->shops()->save($shop);
            } else {
                $this->data = $model;
            }
        } else {
            $model = Company::find($id);
            $this->data = $model;

            if (is_null($model)) {
                /** @var \App\Models\Shop $shop */
                $shop = Shop::find($this->shopId);
                $this->create([
                    'name' => $shop->name,
                    'orgnumber' => $shop->orgnumber,
                ]);

                $this->data->shops()->save($shop);

                return $this;
            }

        }

        return $this;
    }

    public function create(array $data)
    {
        $this->data = DB::transaction(function () use ($data) {
            /** @var \App\Models\Company $model */
            $model = Company::create($data);

            if (! isset($data['support_address'])) {
                $data['support_address']['name'] = $data['name'];
            }

            $accountService = AccountService::factory($this->shopId);
            $supportAddress = $accountService->create($data['support_address'])->get();

            $model->supportAddress()->save($supportAddress);

            return $model;
        });

        return $this;
    }

    public function update(int $id, array $data)
    {
        $supportAddressData = $data['support_address'] ?? null;
        unset($data['support_adress']);

        $this->read($id)->get()->update($data);

        if (! is_null($supportAddressData)) {
            $supportAddressData['name'] = $data['name'];
            $accountService = AccountService::factory($this->shopId);
            $accountService->update($supportAddressData['id'], $supportAddressData);
        }

        return $this;
    }

    public function delete(int $id)
    {
        $deleted = $this->read($id)->get()->delete();

        return $deleted;
    }
}
