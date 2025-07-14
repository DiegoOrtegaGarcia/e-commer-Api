<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_lists', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2);
            $table->decimal('final_price', 10, 2);
            $table->string("productName");
            $table->integer("caunt");

            // Relaciones con eliminación segura
            $table->foreignId("order_id")
                ->nullable()
                ->constrained("orders")
                ->onDelete("set null");

            $table->foreignId("product_id")
                ->nullable()
                ->constrained("products")
                ->onDelete('set null');

            $table->foreignId("discount_id")
                ->nullable()
                ->constrained("discounts")
                ->onDelete('set null');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_lists');
    }
};
