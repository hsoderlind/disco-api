<?php

namespace App\Models;

use App\Casts\MinorCurrency;
use App\Casts\MinorQuantity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $product_id
 * @property-read int $tax_id
 * @property string $product_name
 * @property int $price
 * @property-read int $price_incl_vat
 * @property-read int $price_incl_vat_formatted
 * @property int $total
 * @property-read int $total_incl_vat
 * @property-read int $total_incl_vat_formatted
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
        'product_id',
        'tax_id',
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
    public function priceInclVat(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calcPriceIncVat()
        );
    }

    public function totalInclVat(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calcTotalInclVat()
        );
    }

    public function priceInclVatFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->calcPriceIncVat(), in: config('disco.currency'))
        );
    }

    public function totalInclVatFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->calcTotalInclVat(), in: config('disco.currency'))
        );
    }

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

    protected function calcPriceIncVat()
    {
        return $this->price + $this->vat;
    }

    protected function calcTotalInclVat(): int
    {
        return $this->total + ($this->vat * $this->quantity);
    }
}
