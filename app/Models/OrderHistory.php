<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property array $edited
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'edited',
        'order_id',
    ];

    protected $casts = [
        'edited' => 'array',
    ];
}
