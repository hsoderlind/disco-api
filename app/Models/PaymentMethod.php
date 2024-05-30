<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property string $name
 * @property-read int $shop_id
 * @property string $title
 * @property string|null $description
 * @property int $sort_order
 * @property bool $active
 * @property int $fee
 * @property class $control_class
 * @property string $component
 * @property string|null $admin_component
 * @property array|null $configuration
 * @property string $version
 * @property \App\Models\Logotype|null $logotype
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class PaymentMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'name';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $with = ['logotype'];

    protected $fillable = [
        'title',
        'description',
        'sort_order',
        'active',
        'fee',
        'component',
        'admin_component',
        'configuration',
    ];

    protected $casts = [
        'configuration' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Relationships
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function logotype(): MorphOne
    {
        return $this->morphOne(Logotype::class, 'logotypeable');
    }

    // Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }
}
