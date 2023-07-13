<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property array $phone
 * @property ?string $email
 *
 * @property ?string $telegram
 * @property ?string $vk
 * @property ?string $app_store
 * @property ?string $google_play
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

    protected $casts = [
        'phone' => 'array'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('og_image')
            ->singleFile();

        $this->addMediaCollection('contact_file')
            ->singleFile();
    }
}
