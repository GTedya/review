<?php

namespace App\Models;

use App\Events\OrderLeasingCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property float $advance
 * @property float $sum
 * @property ?int $months
 * @property ?string $current_lessors
 * @property Order $order
 */
class OrderLeasing extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $dispatchesEvents = [
        'created' => OrderLeasingCreated::class,
    ];
    protected $fillable = [
        'order_id',
        'advance',
        'months',
        'sum',
        'current_lessors',
    ];
    protected $casts = [
        'advance' => 'float',
        'sum' => 'float',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
