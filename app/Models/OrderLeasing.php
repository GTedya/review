<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property float $advance
 * @property ?int $months
 * @property ?string $current_lessors
 * @property Order $order
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
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }
}
