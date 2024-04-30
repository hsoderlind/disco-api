<?php

namespace App\Discogs;

use Hsoderlind\Discogs\Api\LoadResourceDefs;
use Illuminate\Support\Str;
use RuntimeException;

class Service
{
    protected string $serviceName;

    protected array $resourceDefs;

    protected array $attributes = [];

    protected array $reservedAttributes = ['username'];

    protected array $modelAttributes = [];

    public function __construct(
        protected readonly ServiceProvider $serviceProvider
    ) {
        $this->loadResourceDefs();
        $this->loadAttributesFromModel();
    }

    protected function loadResourceDefs()
    {
        $this->resourceDefs = new LoadResourceDefs($this->getServiceName());
    }

    protected function loadAttributesFromModel()
    {
        $this->modelAttributes['username'] = $this->serviceProvider->model->getUsername();
    }

    public function getServiceName(): string
    {
        return $this->serviceName ?? Str::lower(class_basename($this));
    }

    public function call(string $action)
    {
        $method = 'use'.ucfirst($this->serviceName);

        if (! method_exists($this->serviceProvider->client, $method)) {
            throw new RuntimeException('A service with name '.$this->serviceName.' do not exist');
        }

        if (! array_key_exists($action, $this->resourceDefs)) {
            throw new RuntimeException('Can not call '.$action.' on '.get_class($this));
        }

        $arguments = $this->pickAttributesForAction($action);
        $response = $this->serviceProvider->client->$method->$action(...$arguments);

        return $response;
    }

    protected function pickAttributesForAction(string $action): array
    {
        $parameters = $this->resourceDefs[$action]['parameters'];
        $actionAttributes = [];

        if (isset($parameters)) {
            foreach ($parameters as $key => $value) {
                $actionAttributes[$key] = $this->attributes[$key];
            }
        }

        if (count($this->reservedAttributes)) {
            foreach ($this->reservedAttributes as $resAttr) {
                $actionAttributes[$resAttr] = $this->attributes[$resAttr];
            }
        }

        return $actionAttributes;
    }

    public function setAttribute($name, $value)
    {
        if (isset($this->reservedAttributes[$name])) {
            throw new RuntimeException('Property '.$name.' is read-only on class '.get_class($this));
        }

        $this->attributes[$name] = $value;
    }

    public function getAttribute($name)
    {
        if (! isset($this->attributes[$name])) {
            throw new RuntimeException('No attribute '.$name.' on class '.get_class($this));
        }

        return $this->attributes;
    }

    public function fill(array $values)
    {
        foreach ($values as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function __call($name, $arguments)
    {
        return $this->call($name);
    }

    public static function make(\Illuminate\Database\Eloquent\Model $model): static
    {
        $serviceProvider = new ServiceProvider($model);

        return new static($serviceProvider);
    }
}
