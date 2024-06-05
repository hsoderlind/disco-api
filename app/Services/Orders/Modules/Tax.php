<?php

namespace App\Services\Orders\Modules;

use App\Services\Orders\Module;

class Tax extends Module
{
    public function getTitle(): string
    {
        return 'Moms';
    }

    public function getDescription(): ?string
    {
        return 'Summering av moms i valuta.';
    }

    public function getCheckoutComponent(): string
    {
        return 'components/order-totals/modules/tax/checkout';
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
        return '2024-05-31';
    }
}
