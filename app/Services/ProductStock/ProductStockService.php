<?php

namespace App\Services\ProductStock;

use App\Models\ProductStock;
use App\Services\AbstractService;

class ProductStockService extends AbstractService
{
    public function newModel(array $data)
    {
        /** @var \App\Models\ProductStock */
        $this->data = new ProductStock(
            collect($data)
                ->only([
                    'sku',
                    'min_order_quantity',
                    'out_of_stock_message',
                    'allow_order_out_of_stock',
                    'send_email_out_of_stock',
                    'in_stock_message',
                    'available_at',
                ])
                ->toArray()
        );

        $this->data->initial_quantity = $data['initial_quantity'];

        return $this;
    }
}
