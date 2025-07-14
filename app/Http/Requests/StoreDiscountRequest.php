<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscountRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => [
                "required",
                "string",
                "max:255",
                Rule::unique('discounts')->whereNull('deleted_at')
            ],
            "percent" => [
                "required",
                "numeric",
                "between:0.01,100.00",
                "regex:/^\d{1,3}(\.\d{1,2})?$/" // Formato decimal con hasta 2 decimales
            ],
            "active" => ["required", "boolean"],
        ];
    }

    public function messages()
    {
        return [
            'percent.regex' => 'El porcentaje debe tener hasta 3 dígitos enteros y 2 decimales',
            'name.unique' => 'Ya existe un descuento con este nombre',
        ];
    }
}
