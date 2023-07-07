<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOfferRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:pdf'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Это поле является обязательным',

            'file' => 'Укажите файл',
            'mimes' => 'Неверный формат',
        ];
    }
}
