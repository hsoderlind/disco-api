<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property-read \App\Models\File $meta
 */
class Logotype extends Model
{
    use HasFactory;

    protected $with = ['meta'];

    // Relationships
    public function meta(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function logotypeable(): MorphTo
    {
        return $this->morphTo('logotypeable');
    }
}
