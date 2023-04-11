<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property ?string $content
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?array $meta
 * @property Collection<File> $files
 * @property ?Media $media
 */
class Page extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta',
        'created_at',
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
}
