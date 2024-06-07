<?php

namespace App\Services\PaymentMethod;

use App\Models\Logotype;
use App\Models\Order;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Services\PaymentMethod\Interfaces\PaymentMethod;
use App\Services\Shop\ShopSession;
use Illuminate\Support\Str;

abstract class Module implements PaymentMethod
{
    protected ?PaymentMethodModel $model;

    public function __construct()
    {
        $this->model = PaymentMethodModel::inShop(ShopSession::getId())->where('name', $this->getName())->first();
    }

    public function onCreating(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onCreated(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onReading(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onRead(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onUpdating(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onUpdated(PaymentMethodModel $model): PaymentMethodModel
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

    public function onInstalling(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onInstalled(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onUninstalling(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onUninstalled(PaymentMethodModel $model): PaymentMethodModel
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

    public function getPresentationComponent(): ?string
    {
        return null;
    }

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
            'presentation_component' => $this->getPresentationComponent(),
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
