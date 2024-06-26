<?php

namespace App\Models;

use App\Helpers\OrgNumberHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property string $name
 * @property string $orgnumber
 * @property string $vat_number
 * @property string|null $official_website
 * @property string|null $support_website
 * @property string|null $terms_of_agreement_url
 * @property string|null $privacy_police_url
 * @property string|null $support_phone
 * @property string|null $support_email
 * @property-read \App\Models\Account $supportAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop[] $shops
 */
class Company extends Model
{
    use HasFactory, LogsActivity;

    protected $with = ['supportAddress'];

    protected $fillable = [
        'name',
        'orgnumber',
        'vat_number',
        'official_website',
        'support_website',
        'terms_of_agreement_url',
        'privacy_police_url',
        'support_phone',
        'support_email',
        'support_address_id',
    ];

    // Attributes
    public function Orgnumber(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => OrgNumberHelper::format($value),
            set: fn (string $value) => OrgNumberHelper::deformat($value),
        );
    }

    // Relationships
    public function supportAddress(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    // Methods
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
