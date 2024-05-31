<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $order_id
 * @property string $old_status
 * @property string $new_status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Note[] $notes
 */
class OrderStatusHistory extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['new_status'];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(fn (OrderStatusHistory $instance) => $instance->old_status = $instance->original['new_status']);
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

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
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
