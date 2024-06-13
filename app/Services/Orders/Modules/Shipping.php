<?php

namespace App\Services\Orders\Modules;

use App\Services\Orders\Module;

class Shipping extends Module
{
    public function getTitle(): string
    {
        return 'Frakt';
    }

    public function getDescription(): ?string
    {
        return 'Visa fraktkostnaden för beställningen bland orderns totalbelopp.';
    }

    public function getCheckoutComponent(): string
    {
        return 'components/order-totals/modules/shipping/checkout';
    }

    public function getPresentationComponent(): ?string
    {
        return null;
    }

    public function getAdminComponent(): ?string
    {
        return null;
    }

    public function getVersion(): string
    {
        return '0.0.1';
    }

    public function getPublishedAt(): string
    {
        return '2024-06-12';
    }
}
