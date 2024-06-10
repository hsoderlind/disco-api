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

        $this->data = $query
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

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
            $controlClass = new $module['control_class']();

            $model->control_class = $module['control_class'];

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
        $this->data = PaymentMethod::inShop($this->shopId)->where('name', $name)->firstOrFail();

        return $this;
    }

    public function update(string $name, array $data)
    {
        $this->data = DB::transaction((function () use ($name, $data) {
            /** @var \App\Models\PaymentMethod $model */
            $model = $this->read($name)->get();

            $model->update(
                collect($data)
                    ->except(['control_class', 'name'])
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

    public function updateCore(string $name)
    {
        /** @var \Illuminate\Support\Collection $modules */
        $modules = PaymentModulesService::factory($this->shopId)->list()->get();

        $module = $modules->where('name', $name)->first();

        /** @var \App\Models\PaymentMethod */
        $model = $this->read($name)->get();

        $model->configuration = ! is_null($module['configuration']) ? [...($model->configuration ?? []), ...$module['configuration']] : $model->configuration;
        $model->component = $module['component'];
        $model->admin_component = $module['admin_component'];
        $model->version = $module['version'];

        $model->save();

        $this->data = $model;

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
