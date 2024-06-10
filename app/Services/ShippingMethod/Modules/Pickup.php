<?php

namespace App\Services\ShippingMethod\Modules;

use App\Services\ShippingMethod\Module;

class Pickup extends Module
{
    public function getTitle(): string
    {
        return 'Hämta i butik';
    }

    public function getDescription(): ?string
    {
        return 'Låt kunden hämta beställningen i butiken.';
    }

    public function getCheckoutComponent(): string
    {
        return 'components/modules/pickup/checkout';
    }

    public function getPublishedAt(): string
    {
        return '2024-06-10';
    }

    public function getVersion(): string
    {
        return '0.0.1';
    }
}
