<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->realText(12),
            'category_id' => rand(1, Category::count()),
            'brand_id' => rand(1, Brand::count()),
            'group_id' => rand(1, Group::count()),
            'type' => $this->faker->randomElement(['standard', 'variant']),
        ];
    }
}
