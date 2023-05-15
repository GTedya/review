<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property int rent_id
 * @property int type_id
 * @property VehicleType $type
 */
class RentVehicle extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'rent_id',
        'type_id',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }
}
