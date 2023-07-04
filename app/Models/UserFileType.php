<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property array $user_type
 */
class UserFileType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'user_type'
    ];

    protected $casts = [
        'user_type' => 'array',
    ];
}
