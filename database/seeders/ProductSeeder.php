<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::factory(100000)->make();

        $chunks = $products->chunk(5000)->toArray();

        foreach ($chunks as $chunk) {
            Product::query()->insert($chunk);
        }
    }
}
