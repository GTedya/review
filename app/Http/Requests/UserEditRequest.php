<?php

namespace App\Http\Requests;

use App\Rules\EditUserFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'min:4'],
            'email' => ['nullable', 'string'],
            'files' => ['nullable', 'array'],
            'files.*' => ['nullable', 'file', new EditUserFileTypeRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Это поле является обязательным',
            'string' => 'Неверный формат',

            'phone.unique' => 'Пользователь с таким номером уже существует',
            'inn.unique' => 'Пользователь с таким инн уже существует',
            'email.email' => 'Неверный формат email',
            'phone.size' => 'Неверный формат',

            'array' => 'Неверный формат',
            'file' => 'Это поле должно быть файлом',

            'name.min' => 'ФИО должно содержать как минимум :min символа',
            'password.min' => 'Пароль должен содержать как минимум :min символов',
        ];
    }
}
