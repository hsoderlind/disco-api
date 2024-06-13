<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property string $name
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $country
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 */
class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'country',
        'phone',
    ];

    /**
     * Get the user that owns the account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Methods
     */
    public function inline(bool $skipName = false)
    {
        $result = collect([
            'name' => $this->name,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'zip' => $this->zip,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
        ])->filter(fn ($value) => ! is_null($value));

        if ($skipName) {
            $result = $result->except(['name']);
        }

        return $result->join(', ');
    }
}
