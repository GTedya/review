<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function vehicles(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'id', 'vehicle_type_id');
    }

    public function orderLeasings(): BelongsTo
    {
        return $this->belongsTo(OrderLeasing::class, 'id', 'order_leasing_id');
    }
}
