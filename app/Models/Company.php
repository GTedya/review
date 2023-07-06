<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $inn
 * @property ?string $org_type
 * @property ?string $org_name
 * @property ?int $geo_id
 * @property int $user_id
 * @property ?Geo $geo
 * @property User $user
 */
class Company extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'inn',
        'org_type',
        'org_name',
        'geo_id',
        'user_id',
    ];

    public function geo(): BelongsTo
    {
        return $this->belongsTo(Geo::class, 'geo_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
