<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug', 100)->unique();

            $table->foreignId('category_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('group_id')->constrained();

            $table->enum('type', [
                'standard', 'variant'
            ])->default('standard');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
