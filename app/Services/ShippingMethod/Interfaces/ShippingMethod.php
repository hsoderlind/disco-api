<?php

namespace App\Services\ShippingMethod\Interfaces;

use App\Models\Logotype;
use App\Models\Order;
use App\Models\ShippingMethodRepository;
use JsonSerializable;

interface ShippingMethod extends JsonSerializable
{
    public function onCreating(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onCreated(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onReading(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onRead(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onUpdating(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onUpdated(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onInitializing(Order $model, array $data): bool|string|null;

    public function onInitialized(Order $model, array $data): bool|string|null;

    public function onProcessing(Order $model): bool|string|null;

    public function onProcessed(Order $model): bool|string|null;

    public function onComplete(Order $model): bool|string|null;

    public function onCompleted(Order $model): bool|string|null;

    public function onInstalling(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onInstalled(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onUninstalling(ShippingMethodRepository $model): ShippingMethodRepository;

    public function onUninstalled(ShippingMethodRepository $model): ShippingMethodRepository;

    public function getName(): string;

    public function getTitle(): string;

    public function getDescription(): ?string;

    public function getConfiguration(): ?array;

    public function getCheckoutComponent(): string;

    public function getAdminComponent(): ?string;

    public function getLogotype(): ?Logotype;

    public function getVersion(): string;

    public function getPublishedAt(): string;

    public function getUpdatedAt(): ?string;

    public function isInstalled(): bool;

    public function updateAvailable(): int;

    public function getChangeLog(): ?string;

    public function toArray(): array;
}
