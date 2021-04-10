<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_products', function (Blueprint $table) {
            $table->id();
            $table->string('NAME');
            $table->string('CATEGORY');
            $table->string('BRAND');
            $table->string('GROUP');
            $table->string('PRODUCT_TYPE');
            $table->string('VARIANT_NAME');
            $table->string('VARIANT_VALUE');
            $table->string('SKU');
            $table->string('BARCODE');
            $table->string('PURCHASE_PRICE');
            $table->string('SELLING_PRICE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_products');
    }
}
