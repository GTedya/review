<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
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
        'about' => 'О нас',
        'search' => 'Подбор',
        'leasings' => 'Лизинг',
        'leasing' => 'Соло лизинга',
    ];

    public const CAN_CREATE = [
        'default' => 'По умолчанию',
        'leasing' => 'Соло лизинга',
    ];

    protected $fillable = [
        'title',
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

    public function pageVar(): hasOne
    {
        return $this->hasOne(PageVar::class);
    }
}
