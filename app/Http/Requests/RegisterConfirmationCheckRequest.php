<?php

namespace App\Http\Requests;

use App\Utilities\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class RegisterConfirmationCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'phone' => Helpers::getCleanPhone($this->phone),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'size:11','exists:users'],
            'code' => ['required', 'string', 'size:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Это поле является обязательным',
            'string' => 'Неверный формат',
            'phone.size' => 'Неверный формат',
            'phone.exists' => 'Пользователь не найден',
            'code.size' => 'Код должен содержать 4 символа'
        ];
    }
}
