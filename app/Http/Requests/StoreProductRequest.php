<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            "price" => ["required", "numeric"],
            "imageUrl" => ["required", "string"]
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            "image_url" => $this->imageUrl
        ]);
    }
}
