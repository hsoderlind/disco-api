<?php

namespace App\Services\Orders;

use App\Exceptions\ShopContextRequiredException;
use App\Http\Resources\OrderTotalModulesCollection;
use App\Http\Resources\OrderTotalModulesResource;
use App\Models\OrderTotalRepository;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;

class ModulesService extends AbstractService
{
    protected $resource = OrderTotalModulesResource::class;

    protected $collectionResource = OrderTotalModulesCollection::class;

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
            /** @var \App\Services\Orders\Interfaces\OrderTotal $module */
            $module = $this->read($name)->get();

            $model = new OrderTotalRepository();

            $module->onUpdating($model);

            $model->fill([
                'title' => $module->getTitle(),
                'description' => $module->getDescription(),
                'sort_order' => 0,
                'active' => true,
                'admin_component' => $module->getAdminComponent(),
                'configuration' => $module->getConfiguration(),
                'version' => $module->getVersion(),
            ]);

            $model->control_class = get_class($module);
            $model->name = $module->getName();
            $model->version = $module->getVersion();

            $model->save();

            $module->onUpdated($model);

            return $model;
        });

        return $this;
    }

    public function update(string $name)
    {
        $this->data = DB::transaction(function () use ($name) {
            /** @var \App\Services\Orders\Interfaces\OrderTotal $module */
            $module = $this->read($name)->get();

            $model = OrderTotalRepository::inShop($this->shopId)->findOrFail($name);

            $module->onUpdating($model);

            $model->fill([
                'admin_component' => $module->getAdminComponent(),
                'configuration' => [...($model->configuration ?? []), ...($module->getConfiguration() ?? [])],
            ]);

            if ($model->control_class !== get_class($module)) {
                $model->control_class = get_class($module);
            }

            $model->version = $module->getVersion();

            $model->save();

            $module->onUpdated($model);

            return $model;
        });

        return $this;
    }

    public function uninstall(string $name)
    {
        $this->data = DB::transaction(function () use ($name) {
            /** @var \App\Services\Orders\Interfaces\OrderTotal $module */
            $module = $this->read($name)->get();

            $model = OrderTotalRepository::inShop($this->shopId)->findOrFail($name);

            $module->onUninstalling($model);

            $model->delete();

            $module->onUninstalled($model);

            return $model;
        });

        return $this;
    }
}
