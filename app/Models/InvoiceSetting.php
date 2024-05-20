<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property bool $show_orgnumber
 * @property bool $show_company_name
 * @property bool $show_shop_name
 * @property bool $show_company_official_website
 * @property bool $show_shop_official_website
 * @property bool $show_company_support_url
 * @property bool $show_shop_support_url
 * @property bool $show_company_terms_of_agreement_url
 * @property bool $show_shop_terms_of_agreement_url
 * @property bool $show_company_privacy_police_url
 * @property bool $show_shop_privacy_police_url
 * @property bool $show_company_support_phone
 * @property bool $show_shop_support_phone
 * @property bool $show_company_support_email
 * @property bool $show_shop_support_email
 * @property bool $show_support_address
 * @property bool $show_shop_address
 * @property-read \App\models\Shop $shop
 * @property-read \App\Models\Logotype $logotype
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class InvoiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_orgnumber',
        'show_company_name',
        'show_shop_name',
        'show_company_official_website',
        'show_shop_official_website',
        'show_company_support_url',
        'show_shop_support_url',
        'show_company_terms_of_agreement_url',
        'show_shop_terms_of_agreement_url',
        'show_company_privacy_police_url',
        'show_shop_privacy_police_url',
        'show_company_support_phone',
        'show_shop_support_phone',
        'show_company_support_email',
        'show_shop_support_email',
        'show_support_address',
        'show_shop_address',
    ];

    protected $casts = [
        'show_orgnumber' => 'boolean',
        'show_company_name' => 'boolean',
        'show_shop_name' => 'boolean',
        'show_company_official_website' => 'boolean',
        'show_shop_official_website' => 'boolean',
        'show_company_support_url' => 'boolean',
        'show_shop_support_url' => 'boolean',
        'show_company_terms_of_agreement_url' => 'boolean',
        'show_shop_terms_of_agreement_url' => 'boolean',
        'show_company_privacy_police_url' => 'boolean',
        'show_shop_privacy_police_url' => 'boolean',
        'show_company_support_phone' => 'boolean',
        'show_shop_support_phone' => 'boolean',
        'show_company_support_email' => 'boolean',
        'show_shop_support_email' => 'boolean',
        'show_support_address' => 'boolean',
        'show_shop_address' => 'boolean',
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
