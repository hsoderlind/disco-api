<?php

namespace App\Services\ProductStock;

use App\Http\Resources\ProductStockResource;
use App\Mail\ProductOutOfStock;
use App\Models\ProductStock;
use App\Services\AbstractService;
use App\Services\Shop\ShopService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProductStockService extends AbstractService
{
    protected $resource = ProductStockResource::class;

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

    public function list()
    {
        $this->data = ProductStock::join('products', 'product_stocks.product_id', '=', 'products.id')
            ->where('product_stocks.shop_id', $this->shopId)
            ->orderBy('products.name')
            ->select('product_stocks.*')
            ->with(['product', 'supplier'])
            ->get();

        return $this;
    }

    public function read(int $id)
    {
        $this->data = ProductStock::inShop($this->shopId)->findOrFail($id);

        return $this;
    }

    public function readByProduct(int $id)
    {
        $this->data = ProductStock::inShop($this->shopId)->forProduct($id)->first();

        return $this;
    }

    public function update(int $id, array $data)
    {
        $this->data = DB::transaction(function () use ($id, $data) {
            $model = $this->read($id)->get();
            $model->update($data);
        });

        return $this;
    }

    public function delete(int $id)
    {
        $deleted = $this->read($id)->get()->delete();

        return $deleted;
    }

    public function canReserveItem(int $productId, int $qty)
    {
        $stock = $this->readByProduct($productId)->get();

        if (is_null($stock)) {
            return true;
        }

        if ($stock->allow_order_out_of_stock) {
            return true;
        }

        return $stock->reserved_quantity + $qty + $stock->sold_quantity <= $stock->initial_quantity;
    }

    public function canPurchaseItem(int $productId, int $qty)
    {
        $stock = $this->readByProduct($productId)->get();

        if (is_null($stock)) {
            return true;
        }

        if ($stock->allow_order_out_of_stock) {
            return true;
        }

        return ($stock->reserved_quantity - $qty) + ($stock->sold_quantity + $qty) <= $stock->initial_quantity;
    }

    public function reserveItem(int $productId, int $qty)
    {
        $stock = $this->readByProduct($productId)->get();

        if (is_null($stock)) {
            return $this;
        }

        $stock->reserved_quantity = $stock->reserved_quantity + $qty;
        $stock->save();

        return $this;
    }

    public function subtractItem(int $productId, int $qty)
    {
        $stock = $this->readByProduct($productId)->get();

        if (is_null($stock)) {
            return $this;
        }

        $stock->reserved_quantity = $stock->reserved_quantity - $qty;
        $stock->sold_quantity = $stock->sold_quantity + $qty;
        $stock->save();

        return $this;
    }

    public function sendOutOfStockMail(int $productId, int $requestedQty)
    {
        /** @var \App\Models\ProductStock */
        $stock = $this->readByProduct($productId)->get();

        if (! $stock->send_email_out_of_stock) {
            return false;
        }

        $shop = ShopService::get($this->shopId);
        $shopName = $shop->name;

        Mail::to($shop->owner)->queue(new ProductOutOfStock(
            $stock->product->name,
            $requestedQty,
            $shopName,
            $shop->owner->name
        ));

        return true;
    }
}
