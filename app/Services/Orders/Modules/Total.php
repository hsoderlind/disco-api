<?php

namespace App\Services\Orders\Modules;

use App\Services\Orders\Module;

class Total extends Module
{
    public function getTitle(): string
    {
        return 'Belopp';
    }

    public function getDescription(): ?string
    {
        return 'Summering av beloppet att betala inklusive moms.';
    }

    public function getCheckoutComponent(): string
    {
        return 'components/order-totals/modules/total/checkout';
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
        return '0.0.2';
    }

    public function getPublishedAt(): string
    {
        return '2024-05-31';
    }
}
