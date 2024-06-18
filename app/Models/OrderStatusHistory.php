<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $order_id
 * @property-read \App\Models\OrderStatus $oldStatus
 * @property-read \App\Models\OrderStatus $newStatus
 * @property-read \App\Models\Note $note
 */
class OrderStatusHistory extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['new_status_id'];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(fn (OrderStatusHistory $instance) => $instance->old_status_id = $instance->original['new_status_id']);
    }

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

    public function oldStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function newStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function note(): MorphOne
    {
        return $this->morphOne(Note::class, 'noteable');
    }

    /**
     * Scopes
     */

    /**
     * Methods
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
