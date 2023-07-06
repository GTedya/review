<?php

namespace App\Models;

use http\Message\Body;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $type_id
 * @property bool $show_in_order
 * @property UserFileType $type
 */
class UserFile extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type_id'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(UserFileType::class);
    }
}
