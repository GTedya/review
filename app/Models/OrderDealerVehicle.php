<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int order_id
 * @property int $type_id
 * @property ?string $brand
 * @property ?string $model
 * @property ?int $count
 * @property VehicleType $type
 * @property Order $order
 */
class OrderDealerVehicle extends Model
{
    use HasFactory;


    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'type_id',
        'brand',
        'model',
        'count',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
