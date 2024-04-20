<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
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
        'available_at',
    ];

    protected $casts = [
        'available_at' => 'date:Y-m-d',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
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
