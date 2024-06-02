<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property int $sort_order
 * @property string $name
 * @property bool $is_default
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort_order',
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
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

    public function validTransitions(): HasMany
    {
        return $this->hasMany(OrderStatusTransition::class, 'from_status_id');
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
    public function canTransitionTo(OrderStatus $toStatus)
    {
        return $this->validTransitions()->where('to_status_id', $toStatus->getKey())->exists();
    }
}
