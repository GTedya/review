<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int order_dealer_id
 * @property int $vehicle_type_id
 * @property ?string $vehicle_brand
 * @property ?string $vehicle_model
 * @property ?int $vehicle_count
 * @property VehicleType $type
 */
class OrderDealerVehicle extends Model
{
    use HasFactory;


    public $timestamps = false;

    protected $fillable = [
        'order_dealer_id',
        'vehicle_type_id',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_count',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }
}
