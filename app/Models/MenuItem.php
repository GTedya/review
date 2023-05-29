<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $sort_index
 * @property string $name
 * @property string $link
 * @property int $group_id
 * @property MenuGroup $group
 */
class MenuItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'group_id',
        'sort_index',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(MenuGroup::class, 'group_id');
    }
}
