<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int|null $id
 * @property-read int $shop_id
 * @property-read int $customer_id
 * @property-read int $current_balance
 * @property string $adjustment_type
 * @property int $adjusted_balance
 * @property string $note
 * @property \App\Models\Customer $customer
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method \Illuminate\Database\Eloquent\Builder|static forCustomer(int $customerId)
 * @method static \Illuminate\Database\Eloquent\Builder|static forCustomer(int $customerId)
 */
class CreditBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'current_balance',
        'adjusted_balance',
        'adjustment_type',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Attributes
    public function currentBalance(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value / 100,
            set: fn (int $value) => $value * 100
        );
    }

    public function adjustedBalance(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value / 100,
            set: fn (int $value) => $value * 100
        );
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(CreditBalance::class, 'customer_id');
    }

    // Local Scopes
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    public function scopeForCustomer(Builder $query, int $customerId)
    {
        return $query->where('customer_id', '=', $customerId);
    }
}
