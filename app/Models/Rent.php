<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $user_id
 * @property int $geo_id
 * @property string $name
 * @property string $phone
 * @property ?string $email
 * @property string $type
 * @property string $title
 * @property ?string $text
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property User $user
 * @property Geo $geo
 *
 */
class Rent extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'geo_id',
        'name',
        'phone',
        'email',
        'type',
        'title',
        'text',
        'created_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function geo(): BelongsTo
    {
        return $this->belongsTo(Geo::class, 'geo_id');
    }

    public function rentVehicles(): HasMany
    {
        return $this->hasMany(RentVehicle::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image');
    }
}
