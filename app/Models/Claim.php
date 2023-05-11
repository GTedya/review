<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $phone
 * @property ?string $name
 * @property ?string $email
 * @property ?string $text
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'name',
        'email',
        'created_at',
        'text',
    ];

    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

}
