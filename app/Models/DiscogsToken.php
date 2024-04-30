<?php

namespace App\Models;

use App\Discogs\Traits\DiscogsTokenizer;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property string $username
 * @property string $token
 * @property string $token_secret
 *
 * @method \Illuminate\Database\Eloquent\Builder|static forShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static forShop(int $shopId)
 */
class DiscogsToken extends Model
{
    use HasFactory, DiscogsTokenizer;

    protected $casts = [
        'token' => 'encrypted',
        'token_secret' => 'encrypted',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($instance) => $instance->shop_id = ShopSession::getId());
    }

    // Scopes
    public function scopeForShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId)->first();
    }
}
