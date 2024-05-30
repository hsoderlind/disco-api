<?php

namespace App\Services\PaymentMethod;

use App\Models\Logotype;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Services\PaymentMethod\Exceptions\MethodNotImplementedException;
use App\Services\PaymentMethod\Interfaces\PaymentMethod;
use App\Services\Shop\ShopSession;
use Illuminate\Support\Str;

class Module implements PaymentMethod
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

    public function onIniting(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onInited(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onProcessing(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
    }

    public function onProcessed(PaymentMethodModel $model): PaymentMethodModel
    {
        return $model;
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

    public function getTitle(): string
    {
        throw MethodNotImplementedException::withMethodName(__FUNCTION__);
    }

    public function getDescription(): ?string
    {
        return null;
    }

    public function getConfiguration(): ?array
    {
        return null;
    }

    public function getCheckoutComponent(): string
    {
        throw MethodNotImplementedException::withMethodName(__FUNCTION__);
    }

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

    public function getVersion(): string
    {
        throw MethodNotImplementedException::withMethodName(__FUNCTION__);
    }

    public function getPublishedAt(): string
    {
        return '-';
    }

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

    public function toArray()
    {
        return $this->jsonSerialize();
    }
}
