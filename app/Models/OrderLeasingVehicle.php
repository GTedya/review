<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_leasing_id
 * @property ?int $vehicle_type_id
 * @property ?string $vehicle_brand
 * @property ?string $vehicle_model
 * @property ?string $vehicle_state
 * @property ?int $vehicle_count
 * @property ?VehicleType $type
 */
class OrderLeasingVehicle extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_leasing_id',
        'vehicle_type_id',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_count',
        'vehicle_state',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
