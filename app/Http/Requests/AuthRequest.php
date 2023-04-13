<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'exists:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле email является обязательным',
            'password.required' => 'Поле пароля является обязательным',

            'string' => 'Неверный формат',

            'email.email' => 'Неверный формат email',
            'email.exists' => 'Неправильный email',
            'pass.min' => 'Пароль должен содержать как минимум :min символов',
        ];
    }
}
