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
        /** @var User $user */
        $user = Auth::user();

        $fileType = explode('.', $attribute)[1] ?? null;
        $fileTypes = UserFileType::query()
            ->where('show_in_order', 1)
            ->whereJsonContains('org_type', $user->company->org_type)
            ->pluck('id');

        if (!$fileTypes->contains($fileType)) {
            $fail('Неверный тип файла');
        }

        if ($user->files->pluck('type_id')->contains($fileType)) {
            $fail('Файл этого типа уже загружен');
        }
    }
}
