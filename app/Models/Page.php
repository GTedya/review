<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property ?int $parent_id
 * @property int $priority
 * @property ?Page $parentDeep
 * @property ?Page $children
 * @property string $template
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?array $meta
 * @property ?PageVar $pageVar
 * @property ?Page $parent
 */
class Page extends Model
{
    use HasFactory;

    public const NAMES = self::CAN_CREATE + [
        'main' => 'Главная',
        'about' => 'О нас',
        'search' => 'Подбор',
        'leasings' => 'Лизинг',
    ];

    public const CAN_CREATE = [
        'default' => 'По умолчанию',
        'leasing' => 'Соло лизинга',
    ];

    protected $fillable = [
        'title',
        'parent_id',
        'priority',
        'template',
        'slug',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
    ];


    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function parentDeep(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id')->with('parentDeep');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function pageVar(): hasOne
    {
        return $this->hasOne(PageVar::class);
    }

    public function fullSlug(): string
    {
        $slugs = [];
        $page = $this;
        $page->parentDeep;
        while ($page?->slug ?? false) {
            $slugs[] = $page->slug;

            /** @var ?Page $parent */
            $page = $page->parentDeep;
        }

        $slugs = array_reverse($slugs);
        $fullSlug = implode('/', $slugs);

        return trim($fullSlug, '/');
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value;
    }
}
