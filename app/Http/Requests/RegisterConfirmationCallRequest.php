<?php

namespace App\Http\Requests;

use App\Utilities\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class RegisterConfirmationCallRequest extends FormRequest
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
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'Это поле является обязательным',
            'string' => 'Неверный формат',
            'phone.size' => 'Неверный формат',
        ];
    }
}
