<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $name
 * @property-read int $shop_id
 * @property string $title
 * @property string|null $description
 * @property int $sort_order
 * @property bool $active
 * @property string $control_class
 * @property string $admin_component
 * @property array $configuration
 * @property string $version
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class OrderTotalRepository extends Model
{
    use HasFactory;

    protected $primaryKey = 'name';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'sort_order',
        'active',
        'admin_component',
        'configuration',
    ];

    protected $casts = [
        'configuration' => 'array',
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

    /**
     * Scopes
     */
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    /**
     * Methods
     */
    public function getNestSortOrder()
    {
        $instance = self::inShop($this->shop_id)->latest();
        $sort_order = $instance->sort_order + 1;

        return $sort_order;
    }
}
