<?php

namespace App\Models;

use App\Models\Traits\HasManyProducts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasManyProducts;
}
