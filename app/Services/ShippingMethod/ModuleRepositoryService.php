<?php

namespace App\Services\ShippingMethod;

use App\Exceptions\ShopContextRequiredException;
use App\Http\Resources\ShippingModulesCollection;
use App\Http\Resources\ShippingModulesResource;
use App\Models\ShippingMethodRepository;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;

class ModuleRepositoryService extends AbstractService
{
    protected $resource = ShippingModulesResource::class;

    protected $collectionResource = ShippingModulesCollection::class;

    protected function boot()
    {
        if (is_null($this->shopId)) {
            throw new ShopContextRequiredException('Shop context required');
        }
    }

    public function list()
    {
        $this->data = ModuleRepository::make();

        return $this;
    }

    public function read(string $name)
    {
        $this->data = ModuleRepository::make()->newModuleInstance($name);

        return $this;
    }

    public function install(string $name)
    {
        $this->data = DB::transaction(function () use ($name) {
            /** @var \App\Services\ShippingMethod\Interfaces\ShippingMethod */
            $module = $this->read($name)->get();

            $model = new ShippingMethodRepository();

            $module->onInstalling($model);

            $model->fill([
                'title' => $module->getTitle(),
                'description' => $module->getDescription(),
                'sort_order' => 0,
                'active' => true,
                'component' => $module->getCheckoutComponent(),
                'admin_component' => $module->getAdminComponent(),
                'configuration' => $module->getConfiguration(),
                'version' => $module->getVersion(),
            ]);

            $model->control_class = get_class($module);
            $model->name = $module->getName();

            $model->save();

            $module->onInstalled($model);

            return $model;
        });

        return $this;
    }

    public function update(string $name)
    {
        $this->data = DB::transaction(function () use ($name) {
            /** @var \App\Services\ShippingMethod\Interfaces\ShippingMethod */
            $module = $this->read($name)->get();

            $model = ShippingMethodRepository::inShop($this->shopId)->where('name', $name)->firstOrFail();

            $module->onUpdating($model);

            $model->fill([
                'component' => $module->getCheckoutComponent(),
                'admin_component' => $module->getCheckoutComponent(),
                'configuration' => [...($model->configuration ?? []), ...($module->getConfiguration() ?? [])],
            ]);

            if ($model->control_class !== get_class($module)) {
                $model->control_class = get_class($module);
            }

            $model->version = $module->getVersion();

            $model->save();

            $module->onUpdated($model);
        });

        return $this;
    }

    public function uninstall(string $name)
    {
        $this->data = DB::transaction(function () use ($name) {
            /** @var \App\Services\ShippingMethod\Interfaces\ShippingMethod */
            $module = $this->read($name)->get();

            $model = ShippingMethodRepository::inShop($this->shopId)->where('name', $name)->firstOrFail();

            $module->onUninstalling($model);

            $model->delete();

            $module->onUninstalled($model);

            return $model;
        });

        return $this;
    }
}
