<?php

namespace App\Services\Orders;

use Illuminate\Support\Collection;
use JsonSerializable;
use RuntimeException;

/**
 * @property-read \Illuminate\Support\Collection $modules
 */
class ModuleRepository implements JsonSerializable
{
    protected Collection $modules;

    private static $instance = null;

    public static function make(): ModuleRepository
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->modules = $this->collect(config('order_total.modules'));
    }

    public function collect(array $modules)
    {
        $data = [];
        foreach ($modules as $module) {
            $data[] = (new $module())->toArray();
        }

        return new Collection($data);
    }

    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function getModule(string $name): ?array
    {
        return $this->modules->first(function ($module) use ($name) {
            return $module['name'] === $name;
        });
    }

    public function newModuleInstance(string $name): ?Module
    {
        $module = $this->getModule($name);

        if (is_null($module)) {
            return null;
        }

        return new $module['control_class']();
    }

    public function __get($name)
    {
        $method = 'get'.ucfirst($name);

        if (! method_exists($this, $method)) {
            throw new RuntimeException("Call to undefined method `$method` on class ".get_class($this));
        }

        return $this->$method;
    }

    public function jsonSerialize(): array
    {
        return $this->modules->toArray();
    }

    public function toArray(): array
    {
        return $this->jsonSerialize();
    }
}
