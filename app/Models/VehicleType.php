<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property ?VehicleType $parent
 * @property string $name
 */
class VehicleType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    public function parent(): HasOne
    {
        return $this->hasOne(VehicleType::class, 'id', 'parent_id');
    }
}
