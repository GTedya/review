<?php

namespace App\Http\Requests;

use App\Utilities\Helpers;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditOrderRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'phone' => ['required', 'string', 'size:11'],
            'name' => ['required', 'string', 'min:4'],
            'user_comment' => ['nullable', 'string'],
            'end_date' => ['nullable', 'date'],
            'geo_id' => ['nullable', 'int', 'exists:geos,id'],
            'leasing' => ['nullable', 'array:advance,months,current_lessors,user_comment,vehicles'],
            'leasing.sum' => ['required_with:leasing', 'numeric'],
            'leasing.advance' => ['required_with:leasing', 'numeric', 'digits_between:0,100'],
            'leasing.vehicles' => ['required_with:leasing', 'array'],
            'leasing.vehicles.*' => ['array:id,type_id,brand,model,count,state'],
            'leasing.vehicles.*.type_id' => ['required', 'int', 'exists:vehicle_types,id'],
            'leasing.vehicles.*.id' => ['nullable', 'int'],
            'dealer' => ['nullable', 'array:vehicles'],
            'dealer.vehicles' => ['required_with:dealer', 'array'],
            'dealer.vehicles.*' => ['array:id, type_id,brand,model,count'],
            'dealer.vehicles.*.id' => ['nullable', 'int'],
            'dealer.vehicles.*.type_id' => ['required', 'int', 'exists:vehicle_types,id'],
        ];
    }

    public function messages(): array
    {
        return [

            'required' => 'Это поле является обязательным',
            'leasing.advance.required_with' => 'Поле аванса является обязательным',
            'leasing.advance.digits_between' => 'Поле аванса принимает значения от 0 до 100',
            'leasing.sum.required_with' => 'Поле необходимая сумма является обязательным',
            'leasing.vehicles.required_with' => 'Вы не выбрали ни одного ТС',
            'dealer.vehicles.required_with' => 'Вы не выбрали ни одного ТС',
            'leasing.vehicles.*.type_id.required' => 'Выберите тип ТС',
            'dealer.vehicles.*.type_id.required' => 'Выберите тип ТС',

            'string' => 'Неверный формат',
            'int' => 'Неверный формат',
            'numeric' => 'Поле должно быть числом',
            'array' => 'Неверный формат',
            'date' => 'Неверный формат',
            'exists' => 'Указано неверное значение',

            'email.email' => 'Неверный формат email',
            'phone.size' => 'Неверный формат',
            'name.min' => 'ФИО должно содержать как минимум :min символа',
        ];
    }
}
