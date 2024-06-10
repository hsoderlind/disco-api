<?php

namespace App\Services\Orders;

use App\Http\Resources\OrderResource;
use App\Mail\OrderReceipt;
use App\Models\Order;
use App\Services\AbstractService;
use App\Services\Customer\CustomerService;
use App\Services\Note\NoteService;
use App\Services\Orders\Exceptions\OrderItemException;
use App\Services\Orders\Exceptions\OrderPaymentException;
use App\Services\OrderStatus\OrderStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use App\Services\ProductStock\ProductStockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use JsonSerializable;

class OrderService extends AbstractService implements JsonSerializable
{
    protected $resource = OrderResource::class;

    protected ?\App\Services\PaymentMethod\Interfaces\PaymentMethod $paymentControlClass = null;

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

        /** @var \App\Models\PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethodService::factory($this->shopId)->read($data['payment_name'])->get();

        $this->paymentControlClass = new $paymentMethod->control_class();

        $this->data = DB::transaction(function () use ($data) {
            $customer = CustomerService::factory($this->shopId)->read($data['customer_id'])->get();

            /** @var \App\Models\Order $order */
            $order = $customer->orders()->create([]);

            $result = $this->paymentControlClass->onInitializing($order, $data);

            if (isset($result) && $result !== true) {
                throw new OrderPaymentException($order->getKey(), $this->paymentControlClass->getName(), is_string($result) ? $result : null);
            }

            return $order;
        });

        $result = $this->paymentControlClass->onInitialized($this->data, $data);

        if (isset($result) && $result !== true) {
            $this->data->delete();
            throw new OrderPaymentException($this->data->getKey(), $this->paymentControlClass->getName(), is_string($result) ? $result : null);
        }

        return $this;
    }

    public function validateItems(Order $order)
    {
        $productStockService = ProductStockService::factory($this->shopId);

        foreach ($this->inputs['items'] as $item) {
            if (! $productStockService->canReserveItem($item['product_id'], $item['quantity'])) {
                $productStockService->sendOutOfStockMail($item['product_id'], $item['quantity']);
                throw new OrderItemException($order->getKey(), $item['product_id'], 'Inte tillräckligt stort lagerantal för att kunna reservera produkterna.');
            }
        }
    }

    public function updateStock()
    {
        $productStockService = ProductStockService::factory($this->shopId);

        foreach ($this->inputs['items'] as $item) {
            $productStockService->reserveItem($item['product_id'], $item['quantity']);
        }
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
            // TODO: set order status to failed
            throw new OrderPaymentException($order->getKey(), $this->paymentControlClass->getName(), is_string($result) ? $result : null);
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

            if (isset($this->inputs['note'])) {
                NoteService::factory($this->shopId)->setRelationModel($order)->create(['content' => $this->inputs['note']]);
            }

            $order->save();

            $result = $this->paymentControlClass->onProcessed($order);

            if (isset($result) && $result !== true) {
                throw new OrderPaymentException($order->getKey(), $this->paymentControlClass->getName(), is_string($result) ? $result : null);
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

        if (! isset($this->paymentControlClass)) {
            $this->paymentControlClass = new $order->paymentMethod->control_class();
        }

        $result = $this->paymentControlClass->onComplete($order);

        if (isset($result) && $result !== true) {
            throw new OrderPaymentException($order->getKey(), $this->paymentControlClass->getName(), is_string($result) ? $result : null);
        }

        $order->receipt()->create([]);

        $order->save();

        // TODO send order confirmation
        Mail::to($order->customer)->queue(new OrderReceipt($order));

        $this->paymentControlClass->onCompleted($order);

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
        /** @var \App\Models\OrderStatusHistory $orderStatusHistory */
        $orderStatusHistory = $order->statusHistory()->create([]);
        $orderStatusHistory->newStatus()->associate($orderStatus);

        if (! is_null($note)) {
            $order->notes()->create([
                'title' => 'Orderstatusen uppdaterades till '.$orderStatus->name,
                'content' => $note,
            ]);
        }

        if (! is_null($mailContent)) {
            // TODO: Send mail
        }

        return $this;
    }
}
