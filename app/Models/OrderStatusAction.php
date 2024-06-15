<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $order_status_id
 * @property int $sort_order
 * @property-read string $action
 */
class OrderStatusAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'sort_order',
    ];

    /**
     * Boot
     */

    /**
     * Attributes
     */

    /**
     * Relationships
     */
    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     * Scopes
     */

    /**
     * Methods
     */
}
