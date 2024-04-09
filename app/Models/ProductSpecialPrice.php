<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read int $id
 * @property-read int $shopId
 * @property int $special_price
 * @property string $entry_date
 * @property string $expiration_date
 * @property string $created_at
 * @property string $updated_at
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
        'entry_date',
        'expiration_date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Relationships
    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }
}
