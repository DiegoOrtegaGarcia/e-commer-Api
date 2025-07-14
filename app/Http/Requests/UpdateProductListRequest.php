<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductListRequest extends FormRequest
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
            "count" => ["sometimes", "numeric", "min:1"],
            "orderId" => ["sometimes", "numeric", "exists:orders,id"],
            "productId" => ["sometimes", "numeric", "exists:products,id"],
            "discountId" => ["sometimes", "nullable", "numeric", "exists:discounts,id"]
        ];
    }

    protected function prepareForValidation()
    {
        $mergeData = [];

        if ($this->has('count')) {
            $mergeData['caunt'] = $this->count; // Mantén el nombre correcto
        }

        if ($this->has('orderId')) {
            $mergeData['order_id'] = $this->orderId;
        }

        if ($this->has('productId')) {
            $mergeData['product_id'] = $this->productId;
        }

        if ($this->has('discountId')) {
            $mergeData['discount_id'] = $this->discountId;
        }

        $this->merge($mergeData);
    }
}
