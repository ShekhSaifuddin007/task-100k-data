<?php

namespace App\Models\Traits;

use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyProducts
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
