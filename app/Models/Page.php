<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
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
