<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property bool $show_in_order
 * @property string $name
 * @property array $org_type
 */
class UserFileType extends Model
{
    use HasFactory;

    public const ORG_TYPES = [
        'ip' => 'ИП',
        'ooo' => 'ООО',
    ];

    public $timestamps = false;

    public $fillable = [
        'name',
        'show_in_order',
        'org_type'
    ];

    protected $casts = [
        'org_type' => 'array',
    ];
}
