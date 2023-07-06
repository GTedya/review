<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property array org_type
 */
class UserFileType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'org_type'
    ];

    protected $casts = [
        'org_type' => 'array',
    ];
}
