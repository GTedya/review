<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
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
 * @property string $slug
 * @property bool $is_published
 * @property bool $with_nds
 * @property ?string $text
 * @property ?Carbon $created_at
 * @property ?Carbon $active_until
 * @property ?Carbon $updated_at
 * @property User $user
 * @property Geo $geo
 * @property Collection<RentVehicle> $rentVehicles
 */
class Rent extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Sluggable;

    protected $fillable = [
        'is_published',
        'user_id',
        'geo_id',
        'name',
        'with_nds',
        'phone',
        'email',
        'type',
        'title',
        'text',
        'active_until',
        'slug',
        'created_at'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

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
        $this->addMediaCollection('images');
    }

    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

    public function getActiveUntilAttribute($value)
    {
        return $value;
    }
}
