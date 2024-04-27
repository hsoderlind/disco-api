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
 * @property string $stock_unit
 * @property string $out_of_stock_message
 * @property string $available_at
 * @property bool $allow_order_out_of_stock
 * @property int $initial_quantity
 * @property int $reserved_quantity
 * @property int $sold_quantity
 * @property-read \App\Models\AttributeProduct $productAttribute
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class AttributeStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'stock_unit',
        'out_of_stock_message',
        'allow_order_out_of_stock',
        'initial_quantity',
        'reserved_quantity',
        'sold_quantity',
    ];

    protected $casts = [
        'available_at' => 'datetime',
        'allow_order_out_of_stock' => 'boolean',
        'sku' => 'string',
        'stock_unit' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Relationships
    public function attributeProduct(): BelongsTo
    {
        return $this->belongsTo(AttributeProduct::class);
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }
}
