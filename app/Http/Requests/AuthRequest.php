<?php

namespace App\Http\Requests;

use App\Utilities\Helpers;
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
            'phone' => ['required', 'string','size:11' ,'exists:users'],
            'password' => ['required', 'string', 'min:8'],
            'device_key' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Поле номера телефона является обязательным',
            'password.required' => 'Поле пароля является обязательным',

            'string' => 'Неверный формат',
            'size' => 'Неверный формат',

            'phone.exists' => 'Неверный номер телефона',
            'password.min' => 'Пароль должен содержать как минимум :min символов',
        ];
    }
}
