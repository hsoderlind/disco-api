<?php

namespace App\Services\ShippingMethod;

use App\Models\Logotype;
use App\Models\Order;
use App\Models\ShippingMethodRepository;
use App\Services\ShippingMethod\Interfaces\ShippingMethod;
use App\Services\Shop\ShopSession;
use Illuminate\Support\Str;

abstract class Module implements ShippingMethod
{
    protected ?ShippingMethodRepository $model;

    public function __construct()
    {
        $this->model = ShippingMethodRepository::inShop(ShopSession::getId())->where('name', $this->getName())->first();
    }

    public function onCreating(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onCreated(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onReading(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onRead(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onUpdating(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onUpdated(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onInitializing(Order $model, array $data): bool|string|null
    {
        return true;
    }

    public function onInitialized(Order $model, array $data): bool|string|null
    {
        return true;
    }

    public function onProcessing(Order $model): bool|string|null
    {
        return true;
    }

    public function onProcessed(Order $model): bool|string|null
    {
        return true;
    }

    public function onComplete(Order $model): bool|string|null
    {
        return true;
    }

    public function onCompleted(Order $model): bool|string|null
    {
        return true;
    }

    public function onInstalling(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onInstalled(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onUninstalling(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function onUninstalled(ShippingMethodRepository $model): ShippingMethodRepository
    {
        return $model;
    }

    public function getName(): string
    {
        return Str::lower(class_basename($this));
    }

    abstract public function getTitle(): string;

    public function getDescription(): ?string
    {
        return null;
    }

    public function getConfiguration(): ?array
    {
        return null;
    }

    abstract public function getCheckoutComponent(): string;

    public function getAdminComponent(): ?string
    {
        return null;
    }

    public function getLogotype(): ?Logotype
    {
        return null;
    }

    abstract public function getVersion(): string;

    abstract public function getPublishedAt(): string;

    public function getUpdatedAt(): ?string
    {
        return null;
    }

    final public function isInstalled(): bool
    {
        return ! is_null($this->model);
    }

    final public function updateAvailable(): int
    {
        return is_null($this->model) ? -1 : (version_compare($this->model->version, $this->getVersion(), '<') ? 1 : 0);
    }

    public function getChangeLog(): ?string
    {
        return null;
    }

    public function jsonSerialize(): mixed
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'control_class' => get_class($this),
            'component' => $this->getCheckoutComponent(),
            'admin_component' => $this->getAdminComponent(),
            'configuration' => $this->getConfiguration(),
            'logotype' => $this->getLogotype(),
            'installed' => $this->isInstalled(),
            'published_at' => $this->getPublishedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'version' => $this->getVersion(),
            'update_available' => $this->updateAvailable(),
        ];
    }
}
