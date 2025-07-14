<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProducListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "price" => $this->price,
            "finalPrice" => $this->final_price,
            "productName" => $this->productName,
            "count" => $this->caunt,
            "orderId" => $this->order_id,
            "productId" => $this->product_id,
            "discountId" => $this->discount_id,
        ];
    }
}
