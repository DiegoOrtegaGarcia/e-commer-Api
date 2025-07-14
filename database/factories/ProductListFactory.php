<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductList>
 */
class ProductListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */



    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $price = $product->price;
        $producName = $product->name;

        $quantity = $this->faker->numberBetween(5, 20);
        $baseTotal = $price * $quantity;
        $discountId = null;
        $finalPrice = $baseTotal;

        // 30% de probabilidad de aplicar descuento
        if ($this->faker->boolean(30)) {
            $discount = Discount::inRandomOrder()->first() ?? Discount::factory()->create();
            $discountId = $discount->id;

            // Calcular precio con descuento CORRECTO:
            $discountedUnitPrice = $price * (1 - ($discount->percent / 100));
            $finalPrice = $discountedUnitPrice * $quantity;
        }

        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        return [
            "price" => $price, // Precio unitario original
            "final_price" => $finalPrice, // Precio total final (con descuento aplicado si existe)
            "product_id" => $product->id,
            "discount_id" => $discountId,
            "caunt" => $quantity, // Cantidad de productos
            "order_id" => $order->id,
            "productName" => $producName
        ];
    }
}
