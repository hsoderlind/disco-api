<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property-read int $customer_id
 * @property string $order_number
 * @property int $order_number_serial
 * @property string|null $order_number_prefix
 * @property string|null $order_number_suffix
 * @property-read string $deleted_at
 * @property-read string $created_at
 * @property-read string $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Metadata[] $metadata
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderTotal[] $totals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $items
 * @property-read \App\Models\OrderPayment $payment
 * @property-read \App\Models\OrderShipping $shipping
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderStatusHistory $statusHistory
 * @property-read \App\Models\OrderStatusHistory $currentStatus
 * @property-read \App\Models\Shop $shop
 * @property-read \App\Models\Receipt $receipt
 * @property-read \App\Models\OrderSetting $settings
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->shop_id = ShopSession::getId();

            $prevOrder = static::inShop($instance->shop_id)->latest()->first();

            if (is_null($prevOrder)) {
                $instance->order_number_serial = 1;
            } else {
                $instance->order_number_serial = $prevOrder->order_number_serial + 1;
            }

            $orderSettings = OrderSetting::forShop($instance->shop_id)->first();
            $instance->order_number = $orderSettings->generateOrderNumber($instance->order_number_serial);
            $instance->order_number_prefix = $orderSettings->order_number_prefix;
            $instance->order_number_suffix = $orderSettings->order_number_suffix;
        });
    }

    /**
     * Attributes
     */

    /**
     * Relationships
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function metadata(): MorphMany
    {
        return $this->morphMany(Metadata::class, 'metadataable');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function totals(): HasMany
    {
        return $this->hasMany(OrderTotal::class)->orderBy('sort_order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class);
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(OrderShipping::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function currentStatus()
    {
        return $this->statusHistory()->latestOfMany();
    }

    public function receipt(): MorphOne
    {
        return $this->morphOne(Receipt::class, 'receiptable');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(OrderSetting::class, 'shop_id', 'shop_id');
    }

    /**
     * Scopes
     */
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    /**
     * Methods
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
