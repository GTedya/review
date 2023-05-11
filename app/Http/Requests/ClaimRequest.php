<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClaimRequest extends FormRequest
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
            'email' => ['nullable', 'string', 'email'],
            'name' => ['nullable', 'string', 'min:4'],
            'text' => ['nullable', 'string'],
            'phone' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [

            'required' => 'Это поле является обязательным',

            'string' => 'Неверный формат',

            'email.email' => 'Неверный формат email',
            'name.min' => 'ФИО должно содержать как минимум :min символа',
        ];
    }
}
