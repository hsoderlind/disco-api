<?php

namespace App\Models;

use App\Services\ShippingMethod\ShippingMethodService;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $order_id
 * @property-read int $shipping_method_repository_id
 * @property string $shipping_method_name
 * @property string $title
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\ShippingMethodRepository $shippingMethodRepository
 */
class OrderShipping extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'shipping_method_name',
        'title',
    ];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $shippingMethod = ShippingMethodService::factory(ShopSession::getId())->read($instance->shipping_method_name)->get();
            $instance->shipping_method_repository_id = $shippingMethod->getKey();
        });
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

    public function shippingMethodRepository(): BelongsTo
    {
        return $this->belongsTo(ShippingMethodRepository::class);
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
