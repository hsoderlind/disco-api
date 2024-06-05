<?php

namespace App\Services\Orders\Interfaces;

use App\Models\Order as Order;
use App\Models\OrderTotalRepository as OrderTotalRepository;
use JsonSerializable;

interface OrderTotal extends JsonSerializable
{
    public function onReading(Order $model): Order;

    public function onRead(Order $model): Order;

    public function onUpdating(OrderTotalRepository $model): OrderTotalRepository;

    public function onUpdated(OrderTotalRepository $model): OrderTotalRepository;

    public function onInitializing(Order $model): Order;

    public function onInitialized(Order $model): Order;

    public function onProcessing(Order $model): Order;

    public function onProcessed(Order $model): Order;

    public function onInstalling(OrderTotalRepository $model): OrderTotalRepository;

    public function onInstalled(OrderTotalRepository $model): OrderTotalRepository;

    public function onUninstalling(OrderTotalRepository $model): OrderTotalRepository;

    public function onUninstalled(OrderTotalRepository $model): OrderTotalRepository;

    public function getName(): string;

    public function getTitle(): string;

    public function getDescription(): ?string;

    public function getConfiguration(): ?array;

    public function getCheckoutComponent(): string;

    public function getPresentationComponent(): ?string;

    public function getAdminComponent(): ?string;

    public function getVersion(): string;

    public function getPublishedAt(): string;

    public function getUpdatedAt(): ?string;

    public function isInstalled(): bool;

    public function updateAvailable(): int;

    public function getChangeLog(): ?string;
}
