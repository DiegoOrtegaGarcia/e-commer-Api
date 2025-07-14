<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory;
    use SoftDeletes;

    public function productList(): BelongsTo
    {
        return $this->belongsTo(ProductList::class);
    }

    protected $fillable = [
        "name",
        "percent",
        "active"
    ];
}
