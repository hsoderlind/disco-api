<?php

namespace App\Services\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\AbstractService;
use App\Services\Customer\CustomerService;
use App\Services\Note\NoteService;
use App\Services\Orders\Exceptions\OrderItemException;
use App\Services\Orders\Exceptions\OrderPaymentException;
use App\Services\Orders\Exceptions\OrderShippingException;
use App\Services\OrderStatus\OrderStatusActionService;
use App\Services\OrderStatus\OrderStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use App\Services\ProductStock\ProductStockService;
use App\Services\ShippingMethod\Interfaces\ShippingMethod;
use App\Services\ShippingMethod\ShippingMethodService;
use Illuminate\Support\Facades\DB;
use JsonSerializable;

class OrderService extends AbstractService implements JsonSerializable
{
    protected $resource = OrderResource::class;

    protected ?\App\Services\PaymentMethod\Interfaces\PaymentMethod $paymentControlClass = null;

    protected ?ShippingMethod $shippingControlClass = null;

    protected array $inputs = [];

    public function jsonSerialize(): mixed
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'data' => ! is_null($this->data) && method_exists($this->data, 'toArray') ? $this->data->toArray() : serialize($this->data),
            'paymentControlClass' => ! is_null($this->paymentControlClass) ? get_class($this->paymentControlClass) : null,
            'shippingControlClass' => ! is_null($this->shippingControlClass) ? get_class($this->shippingControlClass) : null,
        ];
    }

    public function list()
    {
        $this->data = Order::inShop($this->shopId)->with([
            'customer',
            'currentStatus',
            'items',
        ])->get();

        return $this;
    }

    public function initOrder(array $data)
    {
        $this->inputs = $data;

        /** @var \App\Models\PaymentMethod */
        $paymentMethod = PaymentMethodService::factory($this->shopId)->read($data['payment_name'])->get();

        $this->paymentControlClass = new $paymentMethod->control_class();

        /** @var \App\Models\ShippingMethodRepository */
        $shippingMethod = ShippingMethodService::factory($this->shopId)->read($data['shipping_name'])->get();

        $this->shippingControlClass = new $shippingMethod->control_class();

        $this->data = DB::transaction(function () use ($data) {
            $customer = CustomerService::factory($this->shopId)->read($data['customer_id'])->get();

            /** @var \App\Models\Order $order */
            $order = $customer->orders()->create([]);

            $result = $this->paymentControlClass->onInitializing($order, $data);

            if (isset($result) && $result !== true) {
                throw $this->orderPaymentException($order, $result);
            }

            $result = $this->shippingControlClass->onInitializing($order, $data);

            if (isset($result) && $result !== true) {
                throw $this->orderShippingException($order, $result);
            }

            return $order;
        });

        $result = $this->paymentControlClass->onInitialized($this->data, $data);

        if (isset($result) && $result !== true) {
            $this->data->delete();
            throw $this->orderPaymentException($this->data, $result);
        }

        $result = $this->shippingControlClass->onInitialized($this->data, $data);

        if (isset($result) && $result !== true) {
            $this->data->delete();
            throw $this->orderShippingException($this->data, $result);
        }

        return $this;
    }

    public function processOrder(int|Order $order)
    {
        if (is_int($order)) {
            $order = Order::inShop($this->shopId)->findOrFail($order);
        }

        try {
            $this->validateItems($order);
        } catch (OrderItemException $e) {
            $order->delete();
            throw $e;
        }

        $result = $this->paymentControlClass->onProcessing($order);

        if (isset($result) && $result !== true) {
            $this->failOrder($order);
            throw $this->orderPaymentException($order, $result);
        }

        $result = $this->shippingControlClass->onProcessing($order);

        if (isset($result) && $result !== true) {
            $this->failOrder($order);
            throw $this->orderShippingException($order, $result);
        }

        $this->data = DB::transaction(function () use ($order) {
            $order->items()->createMany($this->inputs['items']);

            $this->updateStock();

            $nextSortOrder = 0;
            foreach ($this->inputs['totals'] as $value) {
                $nextSortOrder += $value['sort_order'];
                $orderTotals = [];

                foreach ($value['entries'] as $entry) {
                    $nextSortOrder += $entry['sort_order'];

                    $orderTotals[] = [
                        'name' => $value['name'],
                        'label' => $entry['label'],
                        'value' => $entry['value'],
                        'vat' => $entry['vat'] ?? null,
                        'sort_order' => $nextSortOrder,
                    ];
                }

                $order->totals()->createMany($orderTotals);
            }

            $orderStatus = OrderStatusService::factory($this->shopId)->readDefault()->get();
            $order->statusHistory()->create(['new_status_id' => $orderStatus->getKey()]);

            $order->payment()->create([
                'payment_method_name' => $this->paymentControlClass->getName(),
                'title' => $this->paymentControlClass->getTitle(),
            ]);

            $order->shipping()->create([
                'shipping_method_name' => $this->shippingControlClass->getName(),
                'title' => $this->shippingControlClass->getTitle(),
            ]);

            if (isset($this->inputs['note'])) {
                NoteService::factory($this->shopId)->setRelationModel($order)->create(['content' => $this->inputs['note']]);
            }

            $order->save();

            $result = $this->paymentControlClass->onProcessed($order);

            if (isset($result) && $result !== true) {
                throw $this->orderPaymentException($order, $result);
            }

            $result = $this->shippingControlClass->onProcessed($order);

            if (isset($result) && $result !== true) {
                throw $this->orderShippingException($order, $result);
            }

            return $order;
        });

        return $this;
    }

    public function completeOrder(int|Order $order)
    {
        if (is_int($order)) {
            $order = Order::inShop($this->shopId)->findOrFail($order);
        }

        $result = $this->paymentControlClass->onComplete($order);

        if (isset($result) && $result !== true) {
            throw $this->orderPaymentException($order, $result);
        }

        $result = $this->shippingControlClass->onComplete($order);

        if (isset($result) && $result !== true) {
            throw $this->orderShippingException($order, $result);
        }

        $order->save();

        OrderStatusActionService::factory()->runForOrder($order);

        $this->paymentControlClass->onCompleted($order);

        $this->shippingControlClass->onCompleted($order);

        $this->data = $order;

        return $this;
    }

    public function create(array $data)
    {
        $order = $this->initOrder($data)->get();
        $order = $this->processOrder($order)->get();
        $this->completeOrder($order);

        return $this;
    }

    public function read(int $id)
    {
        $this->data = Order::inShop($this->shopId)->findOrFail($id);

        return $this;
    }

    public function updateOrderStatus(int $orderId, int $orderStatusId, ?string $mailContent = null, ?string $note = null)
    {
        /** @var \App\Models\Order $order */
        $order = $this->read($orderId)->get();

        $orderStatus = OrderStatusService::factory($this->shopId)->read($orderStatusId)->get();
        $order->statusHistory()->create(['new_status_id' => $orderStatus->getKey()]);

        if (! is_null($note)) {
            $order->notes()->create([
                'title' => 'Orderstatusen uppdaterades till '.$orderStatus->name,
                'content' => $note,
            ]);
        }

        // TODO: run order status actions

        return $this;
    }

    protected function failOrder(Order $order)
    {
        $orderStatus = OrderStatusService::factory($this->shopId)->readFailed()->get();

        $order->statusHistory()->create(['new_status_id' => $orderStatus->getKey()]);
    }

    protected function validateItems(Order $order)
    {
        $productStockService = ProductStockService::factory($this->shopId);

        foreach ($this->inputs['items'] as $item) {
            if (! $productStockService->canReserveItem($item['product_id'], $item['quantity'])) {
                $productStockService->sendOutOfStockMail($item['product_id'], $item['quantity']);
                throw new OrderItemException($order->getKey(), $item['product_id'], 'Inte tillräckligt stort lagerantal för att kunna reservera produkterna.');
            }
        }
    }

    protected function updateStock()
    {
        $productStockService = ProductStockService::factory($this->shopId);

        foreach ($this->inputs['items'] as $item) {
            $productStockService->reserveItem($item['product_id'], $item['quantity']);
        }
    }

    protected function orderPaymentException(Order $order, mixed $reason = null)
    {
        new OrderPaymentException($order->getKey(), $this->paymentControlClass->getName(), is_string($reason) ? $reason : null);
    }

    protected function orderShippingException(Order $order, mixed $reason = null)
    {
        new OrderShippingException($order->getKey(), $this->shippingControlClass->getName(), is_string($reason) ? $reason : null);
    }
}
