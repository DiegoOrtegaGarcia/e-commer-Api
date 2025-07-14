<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductList;
use App\Models\Discount;
use App\Models\Order;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear 10 usuarios
        $users = User::factory(10)->create();

        // Crear 20 productos
        $products = Product::factory(20)->create();

        // Crear 5 descuentos
        $discounts = Discount::factory(5)->create();

        // Crear 15 órdenes
        $orders = Order::factory(15)->create([
            'user_id' => fn() => $users->random()->id
        ]);

        // Para cada orden, crear múltiples items (ProductList)
        foreach ($orders as $order) {
            // Determinar cuántos productos tendrá esta orden (1-5)
            $itemCount = rand(1, 5);

            // Crear items para la orden
            ProductList::factory($itemCount)->create([
                'product_id' => fn() => $products->random()->id,
                'discount_id' => fn() => rand(0, 1) ? $discounts->random()->id : null
            ]);
        }

        // Crear algunos ProductList adicionales sin orden asociada
        ProductList::factory(10)->create([
            'product_id' => fn() => $products->random()->id,
            'discount_id' => fn() => rand(0, 1) ? $discounts->random()->id : null
        ]);
    }
}
