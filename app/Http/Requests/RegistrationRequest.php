<?php

namespace App\Http\Requests;

use App\Rules\InnSize;
use App\Utilities\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:4'],
            'phone' => ['required', 'string', 'size:11', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'inn' => ['required', 'string', 'unique:companies', new InnSize()]
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

            'name.min' => 'ФИО должно содержать как минимум :min символа',
            'password.min' => 'Пароль должен содержать как минимум :min символов',
        ];
    }

}
