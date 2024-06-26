<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Services\AbstractService;

class AccountService extends AbstractService
{
    public function create($data)
    {
        $this->data = Account::create([
            'name' => $data['name'],
            'address1' => $data['address1'] ?? null,
            'address2' => $data['address2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'zip' => $data['zip'] ?? null,
            'country' => $data['country'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        return $this;
    }

    public function read(int $id)
    {
        $this->data = Account::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $this->read($id);
        $this->data->update([
            'name' => $data['name'],
            'address1' => $data['address1'] ?? null,
            'address2' => $data['address2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'zip' => $data['zip'] ?? null,
            'country' => $data['country'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        return $this;
    }
}
