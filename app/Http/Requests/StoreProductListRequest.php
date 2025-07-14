<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductListRequest extends FormRequest
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
            "count" => ["required", "numeric", "min:1"],
            "orderId" => ["required", "numeric", "exists:orders,id"],
            "productId" => ["required", "numeric", "exists:products,id"],
            "discountId" => ["sometimes", "nullable", "numeric", "exists:discounts,id"]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "caunt" => $this->count,
            "order_id" => $this->orderId,
            "product_id" => $this->productId,
            "discount_id" => $this->discountId
        ]);
    }
}
