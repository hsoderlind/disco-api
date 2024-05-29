<?php

namespace App\Services\PaymentMethod\Modules;

use App\Services\PaymentMethod\Module;

class Invoice extends Module
{
    public function getTitle(): string
    {
        return 'Faktura';
    }

    public function getDescription(): ?string
    {
        return 'Fakturera kund med Disco Faktura';
    }

    public function getCheckoutComponent(): string
    {
        return 'components/payment-methods/modules/invoice/checkout';
    }

    public function getPresentationComponent(): string
    {
        return 'components/payment-methods/modules/invoice/presentation';
    }

    public function getAdminComponent(): string
    {
        return 'components/payment-methods/modules/invoice/admin';
    }

    public function getVersion(): string
    {
        return '0.0.2';
    }

    public function getPublishedAt(): string
    {
        return '2024-05-27';
    }

    public function getUpdatedAt(): ?string
    {
        return '2024-05-28';
    }
}
