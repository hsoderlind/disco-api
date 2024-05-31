<?php

namespace App\Models;

use App\Casts\MinorCurrency;
use App\Casts\MinorQuantity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $product_id
 * @property-read int $tax_id
 * @property string $product_name
 * @property int $price
 * @property int $total
 * @property int $vat
 * @property int $tax_value
 * @property int $quantity
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Tax $tax
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderAttribute[] $itemAttributes
 */
class OrderItem extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'product_name',
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
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function itemAttributes(): HasMany
    {
        return $this->hasMany(OrderAttribute::class);
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
