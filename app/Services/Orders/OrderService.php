<?php

namespace App\Services\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\AbstractService;
use App\Services\Customer\CustomerService;
use App\Services\Note\NoteService;
use App\Services\Orders\Exceptions\OrderPaymentException;
use App\Services\OrderStatus\OrderStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Support\Facades\DB;
use JsonSerializable;

class OrderService extends AbstractService implements JsonSerializable
{
    protected $resource = OrderResource::class;

    protected ?\App\Services\PaymentMethod\Interfaces\PaymentMethod $controlClass = null;

    protected array $inputs = [];

    public function jsonSerialize(): mixed
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'data' => ! is_null($this->data) && method_exists($this->data, 'toArray') ? $this->data->toArray() : serialize($this->data),
            'controlClass' => ! is_null($this->controlClass) ? get_class($this->controlClass) : null,
        ];
    }

    public function list()
    {
        $this->data = Order::inShop($this->shopId)->get();

        return $this;
    }

    public function initOrder(array $data)
    {
        $this->inputs = $data;

        /** @var \App\Models\PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethodService::factory($this->shopId)->read($data['payment_name'])->get();

        $this->controlClass = new $paymentMethod->control_class();

        $this->data = DB::transaction(function () use ($data) {
            $customer = CustomerService::factory($this->shopId)->read($data['customer_id'])->get();

            /** @var \App\Models\Order $order */
            $order = $customer->orders()->create([]);

            $result = $this->controlClass->onInitializing($order, $data);

            if (isset($result) && $result !== true) {
                throw new OrderPaymentException($order->getKey(), $this->controlClass->getName(), is_string($result) ? $result : null);
            }

            return $order;
        });

        $result = $this->controlClass->onInitialized($this->data, $data);

        if (isset($result) && $result !== true) {
            $this->data->delete();
            throw new OrderPaymentException($this->data->getKey(), $this->controlClass->getName(), is_string($result) ? $result : null);
        }

        return $this;
    }

    public function processOrder(int|Order $order)
    {
        if (is_int($order)) {
            $order = Order::inShop($this->shopId)->findOrFail($order);
        }

        if (! isset($this->controlClass)) {
            $this->controlClass = new $order->paymentMethod->control_class();
        }

        $result = $this->controlClass->onProcessing($order);

        if (isset($result) && $result !== true) {
            // TODO: set order status to failed
            throw new OrderPaymentException($order->getKey(), $this->controlClass->getName(), is_string($result) ? $result : null);
        }

        $this->data = DB::transaction(function () use ($order) {
            $order->items()->createMany($this->inputs['items']);

            $nextSortOrder = 0;
            foreach ($this->inputs['totals'] as $value) {
                $nextSortOrder += $value['sort_order'];
                $orderTotals = [];

                foreach ($value['entries'] as $entry) {
                    $nextSortOrder += $entry['sort_order'];

                    $orderTotals[] = [
                        'label' => $entry['label'],
                        'value' => $entry['value'],
                        'sort_order' => $nextSortOrder,
                    ];
                }

                $order->totals()->createMany($orderTotals);
            }

            $orderStatus = OrderStatusService::factory($this->shopId)->readDefault()->get();
            /** @var \App\Models\OrderStatusHistory $orderStatusHistory */
            $order->statusHistory()->create(['new_status_id' => $orderStatus->getKey()]);

            $order->payment()->create(['payment_method_name' => $this->controlClass->getName()]);

            if (isset($this->inputs['note'])) {
                NoteService::factory($this->shopId)->setRelationModel($order)->create(['content' => $this->inputs['note']]);
            }

            $order->save();

            $result = $this->controlClass->onProcessed($order);

            if (isset($result) && $result !== true) {
                throw new OrderPaymentException($order->getKey(), $this->controlClass->getName(), is_string($result) ? $result : null);
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

        if (! isset($this->controlClass)) {
            $this->controlClass = new $order->paymentMethod->control_class();
        }

        $result = $this->controlClass->onComplete($order);

        if (isset($result) && $result !== true) {
            throw new OrderPaymentException($order->getKey(), $this->controlClass->getName(), is_string($result) ? $result : null);
        }

        // TODO: Send order mail

        $this->controlClass->onCompleted($order);

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
