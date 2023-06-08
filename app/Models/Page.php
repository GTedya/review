<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property ?int $parent_id
 * @property string $template
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?array $meta
 * @property ?PageVar $pageVar
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

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function pageVar(): hasOne
    {
        return $this->hasOne(PageVar::class);
    }
}
