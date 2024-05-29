<?php

namespace App\Services\PaymentMethod;

use App\Http\Resources\PaymentMethodCollection;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Services\AbstractService;
use App\Services\Logotype\LogotypeService;
use Illuminate\Support\Facades\DB;

class PaymentMethodService extends AbstractService
{
    protected $resource = PaymentMethodResource::class;

    protected $collectionResource = PaymentMethodCollection::class;

    public function list(?bool $includeInactive = null)
    {
        $query = PaymentMethod::inShop($this->shopId);

        if (! $includeInactive) {
            $query->where('active', true);
        }

        $this->data = $query->get();

        return $this;
    }

    public function install(array $data)
    {
        $this->data = DB::transaction(function () use ($data) {
            $model = new PaymentMethod(
                collect($data)
                    ->except(['control_class', 'name'])
                    ->toArray()
            );

            $model->name = $data['name'];

            $modulesService = PaymentModulesService::factory($this->shopId);

            /** @var \Illuminate\Support\Collection $modules */
            $modules = $modulesService->list()->get();
            $module = $modules->where('name', $data['name'])->first();

            /** @var \App\Services\PaymentMethod\Interfaces\PaymentMethod $controlClass */
            $controlClass = new $data['control_class']();

            $model->control_class = $data['control_class'];

            $model->version = $controlClass->getVersion();

            if (isset($data['logotype'])) {
                $logotypeService = LogotypeService::factory($this->shopId);
                $logotype = $logotypeService->create($data)->get();

                $model->logotype()->save($logotype);
            }

            $model->save();

            return $model;
        });

        return $this;
    }

    public function read(string $name)
    {
        $this->data = PaymentMethod::findOrFail($name);

        return $this;
    }

    public function update(string $name, array $data)
    {
        $this->data = DB::transaction((function () use ($name, $data) {
            /** @var \App\Models\PaymentMethod $model */
            $model = $this->read($name)->get();

            $model->update(
                collect($data)
                    ->except(['control_class'])
                    ->toArray()
            );

            if (isset($data['logotype'])) {
                $logotype = LogotypeService::factory($this->shopId)->read($data['logotype']['id'])->get();
                $model->logotype()->save($logotype);
            }

            return $model;
        }));

        return $this;
    }

    public function uninstall(string $name)
    {
        return DB::transaction(function () use ($name) {
            /** @var \App\Models\PaymentMethod $model */
            $model = $this->read($name)->get();
            $logotypeId = $model->logotype?->getKey();

            $deleted = $model->delete();

            if ($deleted && $logotypeId) {
                LogotypeService::factory($this->shopId)->read($logotypeId)->get()->delete();
            }

            return $deleted;
        });
    }
}