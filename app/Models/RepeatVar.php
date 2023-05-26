<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property Json $vars
 * @property int $page_var_id
 * @property PageVar $pageVar
 */
class RepeatVar extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'vars',
        'page_var_id'
    ];

    protected $casts = [
        'vars' => 'array',
    ];

    public function pageVar(): BelongsTo
    {
        return $this->belongsTo(PageVar::class);
    }
}
