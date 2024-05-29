<?php

namespace App\Services\PaymentMethod\Modules;

use App\Services\PaymentMethod\Module;

class Cash extends Module
{
    public function getTitle(): string
    {
        return 'Kontant betalning';
    }

    public function getDescription(): ?string
    {
        return 'Kunder kan betala med kontanter i kassan.';
    }

    public function getCheckoutComponent(): string
    {
        return 'components/payment-methods/modules/cash';
    }

    public function getPresentationComponent(): string
    {
        return 'components/payment-methods/modules/cash/presentation';
    }

    public function getAdminComponent(): string
    {
        return 'components/payment-methods/modules/cash/admin';
    }

    public function getVersion(): string
    {
        return '0.0.1';
    }

    public function getPublishedAt(): string
    {
        return '2024-05-27';
    }
}
