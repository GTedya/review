<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_bottom
 * @property Collection<MenuItem> $items
 */
class MenuGroup extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_bottom',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'group_id');
    }
}
