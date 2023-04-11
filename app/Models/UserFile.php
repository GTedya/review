<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $type_id
 * @property Collection<Media> $files
 */
class UserFile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = [
        'type_id'
    ];

    public function types(): BelongsTo
    {
        return $this->belongsTo(UserFileTypes::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default');
    }
}
