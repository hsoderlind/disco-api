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

    public function __construct(
        protected readonly ServiceProvider $serviceProvider
    ) {
        $this->loadResourceDefs();
    }

    protected function loadResourceDefs()
    {
        $this->resourceDefs = new LoadResourceDefs($this->getServiceName());
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
}
