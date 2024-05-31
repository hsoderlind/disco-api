<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $order_id
 * @property-read string $payment_method_name
 * @property string $transaction_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read \App\Models\Order $order
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderPaymentHistory $orderPaymentHistory
 * @property-read \App\Models\OrderPaymentHistory $currentStatus
 */
class OrderPayment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'transaction_id',
    ];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(fn (OrderPayment $instance) => $instance->transaction_id = $instance->transaction_id ?? Str::random(32));
    }

    /**
     * Attributes
     */

    /**
     * Relationships
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderPaymentHistory(): HasMany
    {
        return $this->hasMany(OrderPaymentHistory::class);
    }

    public function currentStatus()
    {
        return $this->orderPaymentHistory()->latestOfMany();
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
