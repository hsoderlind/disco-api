<?php

namespace App\Services\InvoiceSettings;

use App\Models\InvoiceSetting;
use App\Services\AbstractService;
use App\Services\Logotype\LogotypeService;

class InvoiceSettingsService extends AbstractService
{
    public function create(array $data)
    {
        $this->data = InvoiceSetting::create($data);

        return $this;
    }

    public function read()
    {
        $model = InvoiceSetting::inShop($this->shopId)->firstOrCreate([]);

        $this->data = $model;

        return $this;
    }

    public function update(array $data)
    {
        $model = $this->read()->get();

        $model->update($data);

        return $this;
    }

    public function setLogotype(array $data)
    {
        $logotypeService = LogotypeService::factory($this->shopId);
        $logotype = $logotypeService->create($data)->get();

        /** @var \App\Models\InvoiceSetting $model */
        $model = $this->read()->get();

        $model->logotype()->save($logotype);

        return $this;
    }
}
