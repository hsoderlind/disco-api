<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'created_at' => $this->created_at,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'metadata' => MetadataResource::collection($this->whenLoaded('metadata')),
            'notes' => NoteResource::collection($this->whenLoaded('notes')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'shipping' => new OrderShippingResource($this->whenLoaded('shipping')),
            'status_history' => OrderStatusHistoryResource::collection($this->whenLoaded('statusHistory')),
            'current_status' => new OrderStatusHistoryResource($this->whenLoaded('currentStatus')),
            'totals' => new OrderTotalCollection($this->whenLoaded('totals')),
            'payment' => new OrderPaymentResource($this->whenLoaded('payment')),
            'receipt' => new ReceiptResource($this->whenLoaded('receipt')),
            'activities' => $this->whenLoaded('activities'),
        ];
    }
}
