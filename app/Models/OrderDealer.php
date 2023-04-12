<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $order_id
 * @property Collection<OrderDealerVehicle> $vehicles
 */
class OrderDealer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id'
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(OrderDealerVehicle::class);
    }
}
