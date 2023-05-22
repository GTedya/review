<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property int $id;
 * @property string $question;
 * @property string $answer;
 * @property int $sort_index;
 */
class Faq extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'question',
        'answer',
        'sort_index',
    ];
}
