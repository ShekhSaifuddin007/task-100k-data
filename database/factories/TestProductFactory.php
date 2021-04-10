<?php

namespace Database\Factories;

use App\Models\TestProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TestProductFactory extends Factory
{
    protected $model = TestProduct::class;
    protected array $colors = [
        'green', 'blue', 'white', 'red'
    ];
    protected array $sizes = [
        'XL', 'M', 'L', 'XXL'
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement(['standard', 'variant']);
        $variantName = '';
        $variantValue = '';


        if ($type === 'variant') {
            $variantName = 'color,size';

            $color = $this->faker->randomElement($this->colors);
            $size = $this->faker->randomElement($this->sizes);

            $variantValue = "$color,$size";
        }

        return [
            'NAME' => $this->faker->name,
            'CATEGORY' => $this->faker->word,
            'BRAND' => $this->faker->company,
            'GROUP' => $this->faker->word,
            'PRODUCT_TYPE' => $type,
            'VARIANT_NAME' => $variantName,
            'VARIANT_VALUE' => $variantValue,
            'SKU' => rand(14, 20),
            'BARCODE' => rand(14, 20),
            'PURCHASE_PRICE' => $purchasePrice = rand(300, 1000),
            'SELLING_PRICE' => rand($purchasePrice, 1500),
        ];
    }
}
