<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property Json $vars
 * @property int $page_id
 * @property Page $page
 */
class PageVar extends Model
{
    use HasFactory;

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
}
