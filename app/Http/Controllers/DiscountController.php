<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountCollection;
use App\Http\Resources\DiscountResource;
use Illuminate\Auth\Events\Validated;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $validated = request()->validate(
            [
                "isactive" => "sometimes|boolean"
            ]
        );
        $query = Discount::query();

        if (isset($validated["isactive"])) {
            $query->where("active", "=", $validated["isactive"]);
        }

        $discounts = $query->paginate(20)->appends(request()->query());
        return new DiscountCollection($discounts);
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
    public function store(StoreDiscountRequest $request)
    {
        return new DiscountResource(Discount::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        return new DiscountResource($discount);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $discount->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
        return response()->noContent();
    }
}
