<?php

namespace App\Http\Requests;

use App\Constants\RentTypeConstants;
use App\Utilities\Helpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentRequest extends FormRequest
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
            'email' => ['string', 'email'],
            'phone' => ['required', 'string', 'size:11'],
            'name' => ['required', 'string', 'min:4'],
            'geo_id' => ['nullable', 'int', 'exists:geos,id'],
            'type' => ['required', Rule::in(array_keys(RentTypeConstants::RENT_TYPES))],
            'text' => ['required', 'string'],
            'title' => ['required', 'string'],
            'with_nds' => ['required', 'boolean'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image'],
            'rent_vehicles' => ['required', 'array'],
            'rent_vehicles.*' => ['array:type_id'],
            'rent_vehicles.*.type_id' => ['required', 'int', 'exists:vehicle_types,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Это поле является обязательным',
            'rent_vehicles.required' => 'Вы не выбрали ни одного ТС',
            'rent_vehicles.*.type_id.required' => 'Выберите тип ТС',

            'string' => 'Неверный формат',
            'int' => 'Неверный формат',
            'array' => 'Неверный формат',
            'image' => 'Неверный формат',
            'exists' => 'Указано неверное значение',

            'type.in' => 'Указано неверное значение',
            'email.email' => 'Неверный формат email',
            'phone.size' => 'Неверный формат',
            'name.min' => 'ФИО должно содержать как минимум :min символа',
            'image.min' => 'Добавьте как минимум :min изображение',
        ];
    }
}
