<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @property-read int $id
 * @property int $shop_id
 * @property string $name
 * @property int $parent
 * @property int $level
 * @property int $sort_order
 * @property string|null $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|self[] $children
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->shop_id = ShopSession::getId();
            $parentCategory = static::find($instance->parent);
            $instance->level = $parentCategory ? $parentCategory->level++ : 0;
        });

        static::updating(function ($instance) {
            if ($instance->parent !== $instance->getOriginal('parent')) {
                $parentCategory = static::find($instance->parent);
                $instance->level = $parentCategory ? $parentCategory->level++ : 0;
            }
        });
    }

    protected $fillable = [
        'name',
        'parent',
        'sort_order',
    ];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    // Methods
    public function getAllChildren(): SupportCollection
    {
        $children = new SupportCollection();

        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }

        return $children;
    }
}
