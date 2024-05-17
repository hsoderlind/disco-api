<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property string|null $title
 * @property string $content
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 *
 * @method \Illuminate\Database\Eloquent\Builder|static forModel(string $relationModelClass, int $relationModelId)
 * @method static \Illuminate\Database\Eloquent\Builder|static forModel(string $relationModelClass, int $relationModelId)
 */
class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
    ];

    // Relationships
    public function noteable(): MorphTo
    {
        return $this->morphTo('noteable');
    }

    // Scopes
    public function scopeForModel(Builder $query, string $relationModelClass, int $relationModelId)
    {
        return $query->where('noteable_type', $relationModelClass)->where('noteable_id', $relationModelId);
    }
}
