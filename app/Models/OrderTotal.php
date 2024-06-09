<?php

namespace App\Models;

use App\Casts\MinorCurrency;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read int $order_id
 * @property string $label
 * @property int $value
 * @property-read int $value_formatted
 * @property int $sort_order
 */
class OrderTotal extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'label',
        'value',
    ];

    protected $casts = [
        'value' => MinorCurrency::class,
    ];

    /**
     * Boot
     */

    /**
     * Attributes
     */
    public function valueFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->value, in: config('disco.currency'))
        );
    }

    /**
     * Relationships
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scopes
     */

    /**
     * Methods
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
