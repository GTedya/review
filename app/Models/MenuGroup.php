<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property ?Carbon $deleted_at
 * @property string $name
 * @property ?string $link
 * @property bool $is_bottom
 * @property Collection<MenuItem> $items
 */
class MenuGroup extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'is_bottom',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'group_id');
    }

    public function scopeHeader(Builder $query): Builder
    {
        return $query->where('is_bottom', false);
    }

    public function scopeFooter(Builder $query): Builder
    {
        return $query->where('is_bottom', true);
    }
}
