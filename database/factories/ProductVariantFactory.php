<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'product_id' => rand(1, Product::count()),
            'name' => $this->faker->name,
            'value' => $this->faker->word,
            'sku' => Str::random(20),
            'barcode' => Str::random(20),
            'price' => rand(300, 100)
        ];
    }
}
