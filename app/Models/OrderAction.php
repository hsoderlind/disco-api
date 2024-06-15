<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $order_id
 * @property-read int $order_status_id
 * @property int $sort_order
 * @property-read string $action
 * @property-read bool $completed
 * @property-read \Illuminate\Support\Carbon|null $completed_at
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 */
class OrderAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_status_id',
        'action',
        'sort_order',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
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
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

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
    public function complete()
    {
        $this->completed = true;
        $this->completed_at = now();
        $this->save();
    }
}
