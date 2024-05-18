<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $name
 * @property string $orgnumber
 * @property string|null $official_website
 * @property string|null $support_website
 * @property string|null $terms_of_agreement_url
 * @property string|null $privacy_police_url
 * @property string|null $support_phone
 * @property string|null $support_email
 * @property-read int|null $support_address_id
 * @property-read \App\Models\Account $supportAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop[] $shops
 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'orgnumber',
        'official_website',
        'support_website',
        'terms_of_agreement_url',
        'privacy_police_url',
        'support_phone',
        'support_email',
        'support_address_id',
    ];

    // Relationships
    public function supportAddress(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }
}
