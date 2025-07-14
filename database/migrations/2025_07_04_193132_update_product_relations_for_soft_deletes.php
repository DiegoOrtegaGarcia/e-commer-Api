<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Actualizar product_lists
        Schema::table('product_lists', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->foreignId('product_id')
                ->nullable()
                ->change()
                ->constrained("products")
                ->onDelete('set null');

            $table->dropForeign(['discount_id']);
            $table->foreignId('discount_id')
                ->nullable()
                ->change()
                ->constrained("discounts")
                ->onDelete('set null');
        });

        // Actualizar orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')
                ->nullable()
                ->change()
                ->constrained("users")
                ->onDelete('set null');
        });
    }
    public function down()
    {
        // Revertir cambios en product_lists
        Schema::table('product_lists', function (Blueprint $table) {
            $table->dropForeign(["order_id"]);
            $table->foreignId("order_id")
                ->nullable(false)
                ->change()
                ->constrained("orders")
                ->onDelete("cascade");

            $table->dropForeign(['product_id']);
            $table->foreignId('product_id')
                ->nullable(false)
                ->change()
                ->constrained("products")
                ->onDelete('cascade');

            $table->dropForeign(['discount_id']);
            $table->foreignId('discount_id')
                ->nullable(false)
                ->change()
                ->constrained("discounts")
                ->onDelete('cascade');
        });

        // Revertir cambios en orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')
                ->nullable(false)
                ->change()
                ->constrained("users")
                ->onDelete('cascade');
        });
    }
};
