<?php

namespace App\Http\Controllers;

use App\Models\ProductList;
use App\Http\Requests\StoreProductListRequest;
use App\Http\Requests\UpdateProductListRequest;
use App\Http\Resources\ProducListCollection;
use App\Http\Resources\ProducListResource;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProducListCollection(ProductList::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductListRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validated();

            // Obtener modelos (falla rápida si no existen)
            $product = Product::findOrFail($validated["productId"]);
            $order = Order::findOrFail($validated["orderId"]);
            $discount = isset($validated["discountId"])
                ? Discount::findOrFail($validated["discountId"])
                : null;

            // Cálculos
            $price = $product->price;
            $quantity = $validated["count"];
            $finalPrice = $discount
                ? ($price * (1 - $discount->percent / 100)) * $quantity
                : $price * $quantity;

            // Crear ProductList
            return new ProducListResource(ProductList::create([
                "price" => $price,
                "final_price" => $finalPrice,
                "productName" => $product->name,
                "caunt" => $quantity,
                "order_id" => $order->id,
                "product_id" => $product->id,
                'discount_id' => $discount?->id,
            ]));
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductList $productList)
    {
        return new ProducListResource($productList);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductList $productList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductListRequest $request, ProductList $productList)
    {
        return DB::transaction(function () use ($request, $productList) {
            $validated = $request->validated();

            // 1. Manejar el producto - mantener existente si no se proporciona nuevo ID
            $product = $productList->product; // Producto actual por defecto

            if (isset($validated["productId"])) {
                $product = Product::findOrFail($validated["productId"]);
            }

            // 2. Manejar la orden - mantener existente si no se proporciona nuevo ID
            $order = $productList->order; // Orden actual por defecto

            if (isset($validated["orderId"])) {
                $order = Order::findOrFail($validated["orderId"]);
            }

            // 3. Manejar descuento
            $discount = $productList->discount; // Descuento actual por defecto

            if (array_key_exists('discountId', $validated)) {
                $discount = $validated['discountId']
                    ? Discount::findOrFail($validated['discountId'])
                    : null;
            }

            // 4. Manejar cantidad - mantener existente si no se proporciona
            $quantity = $validated["count"] ?? $productList->caunt;

            // 5. Calcular nuevos precios
            $price = $productList->price;
            $finalPrice = $productList->final_price;
            $productName = $productList->productName;

            // Si tenemos un producto, actualizamos el precio unitario y el nombre
            if ($product) {
                $price = $product->price;
                $productName = $product->name;
            }

            // Siempre recalcular si cambió algo relevante
            $recalculate = isset($validated["productId"]) ||
                isset($validated["count"]) ||
                array_key_exists('discountId', $validated);

            if ($recalculate) {
                $finalPrice = $discount
                    ? ($price * (1 - $discount->percent / 100)) * $quantity
                    : $price * $quantity;
            }

            // 6. Actualizar el ProductList
            $updateData = [
                "price" => $price,
                "final_price" => $finalPrice,
                "productName" => $productName,
                "caunt" => $quantity,
            ];

            // Solo actualizar relaciones si se proporcionaron nuevos IDs
            if (isset($validated["productId"])) {
                $updateData["product_id"] = $product->id;
            }
            if (isset($validated["orderId"])) {
                $updateData["order_id"] = $order->id;
            }
            if (array_key_exists('discountId', $validated)) {
                $updateData["discount_id"] = $discount?->id;
            }

            $productList->update($updateData);

            return new ProducListResource($productList->fresh());
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductList $productList)
    {
        $productList->delete();
        return response()->noContent();
    }
}
