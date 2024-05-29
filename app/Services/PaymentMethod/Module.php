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

    public function onUsing(PaymentMethodModel $model): mixed
    {
        return $model;
    }

    public function onUsed(PaymentMethodModel $model): mixed
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

    public function getPresentationComponent(): string
    {
        throw MethodNotImplementedException::withMethodName(__FUNCTION__);
    }

    public function getAdminComponent(): string
    {
        throw MethodNotImplementedException::withMethodName(__FUNCTION__);
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
        return PaymentMethodModel::inShop(ShopSession::getId())->where('name', $this->getName())->exists();
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
        ];
    }

    public function toArray()
    {
        return $this->jsonSerialize();
    }
}
