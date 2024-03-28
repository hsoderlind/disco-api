<?php

namespace App\Models;

use App\Services\Product\ProductCondition;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property int $shop_id
 * @property int $tax_id
 * @property int $supplier_id
 * @property int $manufacturer_id
 * @property int $price
 * @property string|null $reference
 * @property string|null $supplier_reference
 * @property bool $available_for_order
 * @property string $available_at
 * @property string $condition
 * @property string $name
 * @property string|null $summary
 * @property string|null $description
 * @property string|null $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read \App\Models\Tax $tax
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Barcode[] $barcodes
 * @property-read \App\Models\Supplier $supplier
 * @property-read \App\Models\Manufacturer $manufacturer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeProduct[] $productAttributes
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tax_id',
        'supplier_id',
        'manufacturer_id',
        'price',
        'reference',
        'supplier_reference',
        'available_for_order',
        'available_at',
        'condition',
        'name',
        'description',
    ];

    protected $casts = [
        'condition' => ProductCondition::class,
        'available_at' => 'date:Y-m-d',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Relationships
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tax(): HasOne
    {
        return $this->hasOne(Tax::class);
    }

    public function barcodes(): BelongsToMany
    {
        return $this->belongsToMany(Barcode::class);
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }

    public function manufacturer(): HasOne
    {
        return $this->hasOne(Manufacturer::class);
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(AttributeProduct::class);
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    public function scopeInCategories(Builder $query, array $categoryIds)
    {
        return $query->whereHas('categories', fn ($query) => $query->whereIn('id', $categoryIds));
    }
}
