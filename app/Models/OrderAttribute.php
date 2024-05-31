<?php

namespace App\Models;

use App\Casts\MinorCurrency;
use App\Casts\MinorQuantity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $attribute_product_id
 * @property-read int $attribute_type_id
 * @property-read int $attribute_value_id
 * @property-read int $order_item_id
 * @property string $attribute_type_name
 * @property string $attribute_value_name
 * @property int $price
 * @property int $total
 * @property int $vat
 * @property int $tax_value
 * @property int $quantity
 * @property-read \App\Models\OrderItem $orderItem
 * @property-read \App\Models\Order $order
 */
class OrderAttribute extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'attribute_type_name',
        'attribute_value_name',
        'price',
        'total',
        'vat',
        'tax_value',
        'quantity',
    ];

    protected $casts = [
        'price' => MinorCurrency::class,
        'total' => MinorCurrency::class,
        'vat' => MinorQuantity::class,
        'quantity' => MinorQuantity::class,
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
    public function order(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, OrderItem::class);
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
