<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property-read int $cashier_id
 * @property-read string $receipt_number
 * @property-read \App\Models\User $cashier
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class Receipt extends Model
{
    use HasFactory;

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->shop_id = ShopSession::getId();
            $instance->cashier_id = auth()->id();

            $prevReceipt = static::inShop($instance->shop_id)->latest()->first();
            if (is_null($prevReceipt)) {
                $instance->receipt_number = 1;
            } else {
                $instance->receipt_number = $prevReceipt->receipt_number + 1;
            }
            Log::info('Receipt number: '.$instance->receipt_number);
        });
    }

    /**
     * Attributes
     */

    /**
     * Relationships
     */
    public function Cashier(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Receiptable(): MorphTo
    {
        return $this->morphTo();
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
}
