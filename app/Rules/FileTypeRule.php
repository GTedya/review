<?php

namespace App\Rules;

use App\Models\User;
use App\Models\UserFileType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class FileTypeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fileType = explode('.', $attribute)[1] ?? null;
        $fileTypes = UserFileType::query()
            ->where('show_in_order', 1)
            ->pluck('id');

        if (!$fileTypes->contains($fileType)) {
            $fail('Неверный тип файла');
        }
        /** @var User $user */
        $user = Auth::user();
        if ($user->files->pluck('type_id')->contains($fileType)) {
            $fail('Файл этого типа уже загружен');
        }
    }
}
