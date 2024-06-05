<?php

namespace App\Models;

use App\Casts\MinorCurrency;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $shopId
 * @property int $special_price
 * @property string $entry_date
 * @property string $expiration_date
 * @property string $created_at
 * @property string $updated_at
 * @property int $product_id
 * @property \App\Models\Product $product
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class ProductSpecialPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'special_price',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
        'expiration_date' => 'datetime',
        'special_price' => MinorCurrency::class,
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
}
