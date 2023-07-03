<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property string $name
 * @property string $region_code
 * @property ?Geo $parent
 * @property Collection<int, Geo> $children
 * @property Collection<int, Geo> $childrenDeep
 * @property Collection<int, Geo> $parentDeep
 */
class Geo extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'parent_id',
        'region_code'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Geo::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Geo::class, 'parent_id');
    }

    public function childrenDeep(): HasMany
    {
        return $this->hasMany(Geo::class, 'parent_id')->with('childrenDeep');
    }

    public function parentDeep(): BelongsTo
    {
        return $this->belongsTo(Geo::class, 'parent_id')->with('parentDeep');
    }
}
