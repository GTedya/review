<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int rent_id
 * @property int type_id
 */
class RentVehicle extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'rent_id',
        'type_id',
    ];

    public function vehicleTypes(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }
}
