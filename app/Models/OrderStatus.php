<?php

namespace App\Models;

use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read int $shop_id
 * @property int $sort_order
 * @property string $name
 * @property bool $is_default
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderStatusAction $actions
 *
 * @method \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|static inShop(int $shopId)
 * @method \Illuminate\Database\Eloquent\Builder|static isDefault(bool $isDefault = true)
 * @method static \Illuminate\Database\Eloquent\Builder|static isDefault(bool $isDefault = true)
 */
class OrderStatus extends Model
{
    use HasFactory;

    protected $with = [
        'actions',
    ];

    protected $fillable = [
        'sort_order',
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
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
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function validTransitions(): HasMany
    {
        return $this->hasMany(OrderStatusTransition::class, 'from_status_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(OrderStatusAction::class)->orderBy('sort_order');
    }

    /**
     * Scopes
     */
    public function scopeInShop(Builder $query, int $shopId)
    {
        return $query->where('shop_id', '=', $shopId);
    }

    public function scopeIsDefault(Builder $query, $isDefault = true)
    {
        return $query->where('is_default', $isDefault);
    }

    /**
     * Methods
     */
    public function canTransitionTo(OrderStatus $toStatus)
    {
        return $this->validTransitions()->where('to_status_id', $toStatus->getKey())->exists();
    }

    public function syncActions(array $actions)
    {
        $actionsCollection = collect($actions);
        $actionsToDetach = [];
        $actionsToAttach = [];

        foreach ($this->actions as $currentAction) {
            $entry = $actionsCollection->firstWhere('action', $currentAction->action);

            if (is_null($entry)) {
                $actionsToDetach[] = $currentAction;
            }
        }

        foreach ($actionsCollection as $action) {
            $model = $this->actions()->where('action', $action['action'])->first();

            if (is_null($model)) {
                $actionsToAttach[] = $action;
            } elseif ($model->sort_order !== $action['sort_order']) {
                $model->update(['sort_order' => $action['sort_order']]);
            }
        }

        foreach ($actionsToDetach as $action) {
            $action->delete();
        }

        if (count($actionsToAttach)) {
            $this->actions()->createMany($actionsToAttach);
        }
    }
}
