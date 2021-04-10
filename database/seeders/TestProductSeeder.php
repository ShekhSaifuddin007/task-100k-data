<?php

namespace Database\Seeders;

use App\Models\TestProduct;
use Illuminate\Database\Seeder;

class TestProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = TestProduct::factory(100000)->make();

        $chunks = $products->chunk(5000)->toArray();

        foreach ($chunks as $chunk) {
            TestProduct::query()->insert($chunk);
        }
    }
}
