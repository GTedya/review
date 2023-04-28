<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'phone' => ['required', 'string', 'min:10'],
            'name' => ['required', 'string', 'min:4'],
            'inn' => ['nullable', 'string', 'size:12'],
            'org_name' => ['nullable', 'string'],
            'end_date' => ['nullable', 'date'],
            'geo_id' => ['nullable', 'int'],
            'leasing' => ['nullable', 'array:advance,months,current_lessors,user_comment,vehicles'],
            'leasing.advance' => ['required_with:leasing', 'numeric'],
            'leasing.vehicles' => ['required_with:leasing', 'array'],
            'leasing.vehicles.*' => ['array:type_id,brand,model,count,state'],
            'leasing.vehicles.*.type_id' => ['required', 'int'],
            'dealer' => ['nullable', 'array:vehicles'],
            'dealer.vehicles' => ['required_with:dealer', 'array'],
            'dealer.vehicles.*' => ['array:type_id,brand,model,count'],
            'dealer.vehicles.*.type_id' => ['required', 'int'],

        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Поле email является обязательным',
            'name.required' => 'Поле ФИО является обязательным',
            'phone.required' => 'Поле телефона является обязательным',
            'leasing.advance.required_with' => 'Поле аванса является обязательным',
            'leasing.vehicles.required_with' => 'Вы не выбрали ни одного ТС',
            'dealer.vehicles.required_with' => 'Вы не выбрали ни одного ТС',
            'leasing.vehicles.*.type_id.required' => 'Выберите тип ТС',
            'dealer.vehicles.*.type_id.required' => 'Выберите тип ТС',

            'string' => 'Неверный формат',
            'int' => 'Неверный формат',
            'numeric' => 'Поле должно быть числом',
            'array' => 'Неверный формат',
            'date' => 'Неверный формат',

            'inn.size' => 'ИНН должен содержать 12 символов',
            'email.email' => 'Неверный формат email',
            'phone.min' => 'Телефон должен содержать как минимум :min символов',
            'name.min' => 'ФИО должно содержать как минимум :min символов',
        ];
    }
}
