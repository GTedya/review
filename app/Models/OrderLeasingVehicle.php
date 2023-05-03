<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_leasing_id
 * @property int $type_id
 * @property ?string $brand
 * @property ?string $model
 * @property ?string $state
 * @property ?int $count
 * @property VehicleType $type
 */
class OrderLeasingVehicle extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_leasing_id',
        'type_id',
        'brand',
        'model',
        'count',
        'state',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }
}
