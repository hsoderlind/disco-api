<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property string|null $order_number_prefix
 * @property string|null $order_number_suffix
 * @property int $order_number_start
 * @property int $order_number_serial_length
 * @property string $order_number_pattern
 * @property string $purchase_information
 *
 * @method \Illuminate\Database\Eloquent\Builder|static forShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static forShop(int $shopId)
 */
class OrderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number_prefix',
        'order_number_suffix',
        'order_number_start',
        'order_number_serial_length',
        'order_number_pattern',
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

    /**
     * Scopes
     */
    public function scopeForShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    /**
     * Methods
     */
    public function generateOrderNumber(int $serial)
    {
        return Str::replace([
            '[prefix]', '[serial]', '[suffix]',
        ], [
            $this->order_number_prefix,
            Str::repeat('0', $this->order_number_serial_length - Str::length((string) $serial)).(string) $serial,
            $this->order_number_suffix,
        ], $this->order_number_pattern);
    }
}
