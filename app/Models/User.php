<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read mixed $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop[] $shops
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $guard_name = 'sanctum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the account associated with the user.
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * Get the shops associated with the user.
     */
    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }

    // Methods
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
