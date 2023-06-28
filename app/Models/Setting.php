<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $phone
 * @property ?string $email
 *
 * @property ?string $telegram
 * @property ?string $vk
 * @property ?string $app_store
 * @property ?string $google_play
 *
 * @property ?Media $media
 */
class Setting extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = [
        'phone',
        'email',
        'telegram',
        'app_store',
        'google_play',
        'vk',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('og_image')
            ->singleFile();
    }
}
