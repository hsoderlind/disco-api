<?php

namespace App\Models;

use App\Casts\MinorQuantity;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property string $sku
 * @property int $initial_quantity
 * @property int $reserved_quantity
 * @property int $sold_quantity
 * @property int $min_order_quantity
 * @property string $out_of_stock_message
 * @property bool $allow_order_out_of_stock
 * @property bool $send_email_out_of_stock
 * @property string $in_stock_message
 * @property string $available_at
 * @property-read int $disposable_quantity
 * @property-read int $approx_disposable_quantity
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method \Illuminate\Database\Eloquent\Builder|static forProduct(int $productId)
 * @method static \Illuminate\Database\Eloquent\Builder|static forProduct(int $productId)
 */
class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'min_order_quantity',
        'out_of_stock_message',
        'allow_order_out_of_stock',
        'send_email_out_of_stock',
        'in_stock_message',
    ];

    protected $casts = [
        'available_at' => 'datetime',
        'allow_order_out_of_stock' => 'boolean',
        'send_email_out_of_stock' => 'boolean',
        'initial_quantity' => MinorQuantity::class,
        'reserved_quantity' => MinorQuantity::class,
        'sold_quantity' => MinorQuantity::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Accessors & Mutators
    protected function disposableQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->initial_quantity - $this->sold_quantity
        );
    }

    protected function approxDisposableQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->initial_quantity - $this->sold_quantity - $this->reserved_quantity
        );
    }

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    public function scopeForProduct(Builder $query, int $productId)
    {
        return $query->where('product_id', '=', $productId);
    }
}
