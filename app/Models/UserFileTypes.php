<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class UserFileTypes extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
    ];
}
