<?php

namespace App\Models;

use App\Helpers\OrgNumberHelper;
use App\Helpers\SsnHelper;
use App\Services\Shop\ShopService;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int|null $id
 * @property string|null $person_name
 * @property string|null $company_name
 * @property-read string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $ssn
 * @property string|null $orgno
 * @property string|null $vatno
 * @property bool $taxable
 * @property string $currency
 * @property string|null $note
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Account $shippingAddress
 * @property-read \App\Models\Account $billingAddress
 * @property-read \App\Models\CreditBalance $creditBalance
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop[] $shops
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Metadata[] $metadata
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 */
class Customer extends Model
{
    use HasFactory, LogsActivity;

    protected $with = ['shippingAddress', 'billingAddress'];

    protected $fillable = [
        'person_name',
        'company_name',
        'email',
        'password',
        'ssn',
        'orgno',
        'vatno',
        'taxable',
        'currency',
        'note',
    ];

    protected $casts = [
        'taxable' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($instance) {
            $shop = ShopService::get(ShopSession::getId());
            $instance->shops()->attach($shop->getKey());
        });
    }

    // Attributes
    public function Orgno(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ! is_null($value) ? OrgNumberHelper::format($value) : null,
            set: fn (?string $value) => ! is_null($value) ? OrgNumberHelper::deformat($value) : null
        );
    }

    public function Ssn(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ! is_null($value) ? SsnHelper::format($value, true) : null,
            set: fn (?string $value) => ! is_null($value) ? SsnHelper::format($value, false) : null
        );
    }

    public function Name(): Attribute
    {
        return Attribute::make(
            get: fn () => ! is_null($this->company_name) ? $this->company_name : $this->person_name
        );
    }

    // Relationships
    public function creditBalance(): HasOne
    {
        return $this->hasOne(CreditBalance::class)->latestOfMany();
    }

    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'billing_address_id');
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class);
    }

    public function metadata(): MorphMany
    {
        return $this->morphMany(Metadata::class, 'metadataable');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeInShop(Builder $builder, int $shopId)
    {
        return $builder->whereHas('shops', fn ($query) => $query->where('shops.id', $shopId));
    }

    // Methods
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
