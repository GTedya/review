<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property string $name
 * @property ?Geo $parent
 * @property ?Geo $children
 */
class Geo extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Geo::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Geo::class, 'parent_id');
    }

}
