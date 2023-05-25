<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property ?Carbon $deleted_at
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
    ];

    public function group()
    {
        return $this->belongsTo(MenuGroup::class, 'group_id');
    }
}
