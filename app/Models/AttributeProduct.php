<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property int $sort_order
 * @property bool $active
 * @property-read int $product_id
 * @property-read int $attribute_type_id
 * @property-read int $attribute_value_id
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\AttributeType $attribute_type
 * @property-read \App\Models\AttributeValue $attribute_value
 * @property-read \App\Models\AttributeStock $stock
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class AttributeProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort_order',
        'active',
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

    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class);
    }

    public function attributeType(): BelongsTo
    {
        return $this->belongsTo(AttributeType::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(AttributeStock::class);
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }
}
