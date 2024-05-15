<?php

namespace App\Models;

use App\Helpers\OrgNumberHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

/**
 * @property-read int $id
 * @property string $name
 * @property string $orgnumber
 * @property string $url_alias
 * @property string $address_street1
 * @property string|null $address_street2
 * @property string $address_zip
 * @property string $address_city
 * @property int $account_owner
 * @property string|null $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Customer[] $customers
 */
class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->url_alias = Str::slug($instance->name);
        });
    }

    protected $fillable = [
        'name',
        'orgnumber',
        'url_alias',
        'address_street1',
        'address_street2',
        'address_zip',
        'address_city',
    ];

    protected $casts = [
        'account_owner' => 'int',
    ];

    public function Orgnumber(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => OrgNumberHelper::format($value),
            set: fn (string $value) => OrgNumberHelper::deformat($value),
        );
    }

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'account_owner');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, PermissionRegistrar::$teamsKey);
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }
}
