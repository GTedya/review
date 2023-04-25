<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $order_id
 * @property float $advance
 * @property ?int $months
 * @property ?string $current_lessors
 * @property ?string $user_comment
 * @property Order $order
 * @property Collection<OrderLeasingVehicle> $vehicles
 */
class OrderLeasing extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'advance',
        'months',
        'current_lessors',
        'user_comment',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(OrderLeasingVehicle::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }
}
