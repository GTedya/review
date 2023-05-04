<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property ?VehicleType $parent
 * @property ?VehicleType $children
 * @property ?VehicleType $childrenDeep
 * @property string $name
 * @property ?Carbon $deleted_at
 */
class VehicleType extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    public function parent(): HasOne
    {
        return $this->hasOne(VehicleType::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(VehicleType::class, 'parent_id');
    }

    public function childrenDeep(): HasMany
    {
        return $this->hasMany(VehicleType::class, 'parent_id')->with('childrenDeep');
    }
}
