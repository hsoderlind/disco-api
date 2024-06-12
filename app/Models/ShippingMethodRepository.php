<?php

namespace App\Models;

use App\Casts\MinorCurrency;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property-read int $id
 * @property string $name
 * @property-read int $shop_id
 * @property string $title
 * @property string|null $description
 * @property array|null $configuration
 * @property int $fee
 * @property string $component
 * @property string|null $admin_component
 * @property int $sort_order
 * @property bool $active
 * @property string $control_class
 * @property string $version
 * @property-read \App\Models\Logotype|null $logotype
 * @property-read \App\Models\Shop $shop
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class ShippingMethodRepository extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'configuration',
        'fee',
        'sort_order',
        'active',
        'component',
        'admin_component',
        'version',
    ];

    protected $casts = [
        'fee' => MinorCurrency::class,
        'configuration' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    /**
     * Attributes
     */

    /**
     * Relationships
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function logotype(): MorphOne
    {
        return $this->morphOne(Logotype::class, 'logotypeable');
    }

    /**
     * Scopes
     */
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }
}
