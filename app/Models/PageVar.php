<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property ?array $vars
 * @property int $page_id
 * @property Page $page
 * @property Collection<int, RepeatVar> repeatVars
 */
class PageVar extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = [
        'vars',
        'page_id'
    ];

    protected $casts = [
        'vars' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }


    public function repeatVars(): HasMany
    {
        return $this->hasMany(RepeatVar::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_why_us_preview')
            ->singleFile();

        $this->addMediaCollection('about_main_image')
            ->singleFile();

        $this->addMediaCollection('search_main_image')
            ->singleFile();

        $this->addMediaCollection('leasings_main_image')
            ->singleFile();

        $this->addMediaCollection('leasing_main_image')
            ->singleFile();

        $this->addMediaCollection('leasing_description_image')
            ->singleFile();

        $this->addMediaCollection('default_image')
            ->singleFile();
    }
}
