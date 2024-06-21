<?php

namespace App\Models;

use App\Casts\MinorCurrency;
use App\Casts\MinorQuantity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $product_id
 * @property-read int $tax_id
 * @property string $product_name
 * @property string $item_number
 * @property int $price
 * @property-read int $price_incl_vat
 * @property-read int $price_incl_vat_formatted
 * @property int $total
 * @property-read int $total_incl_vat
 * @property-read int $total_incl_vat_formatted
 * @property int $vat
 * @property int $tax_value
 * @property int $quantity
 * @property-read int $batch
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Tax $tax
 *
 * @method \Illuminate\Database\Eloquent\Builder|static latestBatch()
 * @method static \Illuminate\Database\Eloquent\Builder|static latestBatch()
 */
class OrderItem extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'product_id',
        'tax_id',
        'product_name',
        'item_number',
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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->price_incl_vat_formatted = Number::currency($instance->calcPriceIncVat(), in: config('disco.currency'));
            $instance->total_incl_vat_formatted = Number::currency($instance->calcTotalInclVat(), in: config('disco.currency'));

            $prevInstance = static::latestBatch()->first();
            $instance->batch = $prevInstance->batch + 1;
        });

        static::updating(function ($instance) {
            $instance->price_incl_vat_formatted = Number::currency($instance->calcPriceIncVat(), in: config('disco.currency'));
            $instance->total_incl_vat_formatted = Number::currency($instance->calcTotalInclVat(), in: config('disco.currency'));
        });
    }

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

    /**
     * Scopes
     */
    public function scopeLatestBatch(Builder $query)
    {
        return $query->where('batch', function ($query) {
            $query->select(DB::raw('MAX(batch)'))
                ->from('order_items');
        });
    }

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
