<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property-read int $barcode_type_id
 * @property string $value
 * @property string|null $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \App\Models\BarcodeType $barcode_type
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class Barcode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'value',
    ];

    protected $with = [
        'barcodeType',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Relationships
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function barcodeType(): BelongsTo
    {
        return $this->belongsTo(BarcodeType::class);
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }
}
