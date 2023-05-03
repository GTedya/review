<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property ?string $content
 * @property ?Carbon $start_date
 * @property ?Carbon $end_date
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?array $meta
 * @property Collection<File> $files
 */
class News extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'start_date',
        'end_date',
        'created_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];


    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable',);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->withResponsiveImages()
            ->singleFile();
    }

    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

    public function getStartDateAttribute($value)
    {
        return $value;
    }

    public function getEndDateAttribute($value)
    {
        return $value;
    }
}
