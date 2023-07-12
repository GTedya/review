<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InnSize implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        switch (strlen($value)) {
            case 12:
            case 10:
                break;
            default:
                $fail('Поле ИНН должно содержать 10 или 12 символов');
        }
    }
}
