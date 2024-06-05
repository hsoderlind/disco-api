<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderTotalRepository as OrderTotalRepository;
use App\Services\Orders\Interfaces\OrderTotal;
use App\Services\Shop\ShopSession;
use Illuminate\Support\Str;

abstract class Module implements OrderTotal
{
    protected ?OrderTotalRepository $repository;

    public function __construct()
    {
        $this->repository = OrderTotalRepository::inShop(ShopSession::getId())->where('name', $this->getName())->first();
    }

    public function onReading(Order $model): Order
    {
        return $model;
    }

    public function onRead(Order $model): Order
    {
        return $model;
    }

    public function onUpdating(OrderTotalRepository $model): OrderTotalRepository
    {
        return $model;
    }

    public function onUpdated(OrderTotalRepository $model): OrderTotalRepository
    {
        return $model;
    }

    public function onInitializing(Order $model): Order
    {
        return $model;
    }

    public function onInitialized(Order $model): Order
    {
        return $model;
    }

    public function onProcessing(Order $model): Order
    {
        return $model;
    }

    public function onProcessed(Order $model): Order
    {
        return $model;
    }

    public function onInstalling(OrderTotalRepository $model): OrderTotalRepository
    {
        return $model;
    }

    public function onInstalled(OrderTotalRepository $model): OrderTotalRepository
    {
        return $model;
    }

    public function onUninstalling(OrderTotalRepository $model): OrderTotalRepository
    {
        return $model;
    }

    public function onUninstalled(OrderTotalRepository $model): OrderTotalRepository
    {
        return $model;
    }

    public function getName(): string
    {
        return Str::lower(class_basename($this));
    }

    public function getDescription(): ?string
    {
        return null;
    }

    public function getConfiguration(): ?array
    {
        return null;
    }

    public function getUpdatedAt(): ?string
    {
        return null;
    }

    public function isInstalled(): bool
    {
        return ! is_null($this->repository);
    }

    public function updateAvailable(): int
    {
        return is_null($this->repository) ? -1 : (version_compare($this->repository->version, $this->getVersion(), '<') ? 1 : 0);
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
            'admin_component' => $this->getAdminComponent(),
            'configuration' => $this->getConfiguration(),
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
