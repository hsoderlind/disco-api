<?php

namespace App\Services\PaymentMethod\Interfaces;

use App\Models\Logotype;
use App\Models\PaymentMethod as PaymentMethodModel;
use JsonSerializable;

interface PaymentMethod extends JsonSerializable
{
    public function onCreating(PaymentMethodModel $model): PaymentMethodModel;

    public function onCreated(PaymentMethodModel $model): PaymentMethodModel;

    public function onReading(PaymentMethodModel $model): PaymentMethodModel;

    public function onRead(PaymentMethodModel $model): PaymentMethodModel;

    public function onUpdating(PaymentMethodModel $model): PaymentMethodModel;

    public function onUpdated(PaymentMethodModel $model): PaymentMethodModel;

    public function onUsing(PaymentMethodModel $model): mixed;

    public function onUsed(PaymentMethodModel $model): mixed;

    public function onInstalling(PaymentMethodModel $model): PaymentMethodModel;

    public function onInstalled(PaymentMethodModel $model): PaymentMethodModel;

    public function onUninstalling(PaymentMethodModel $model): PaymentMethodModel;

    public function onUninstalled(PaymentMethodModel $model): PaymentMethodModel;

    public function getName(): string;

    public function getTitle(): string;

    public function getDescription(): ?string;

    public function getConfiguration(): ?array;

    public function getCheckoutComponent(): string;

    public function getPresentationComponent(): string;

    public function getAdminComponent(): string;

    public function getLogotype(): ?Logotype;

    public function getVersion(): string;

    public function getPublishedAt(): string;

    public function getUpdatedAt(): ?string;

    public function isInstalled(): bool;
}