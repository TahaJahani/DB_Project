<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->id('product_id');
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('sale_price');
            $table->unsignedBigInteger('purchase_price');
            $table->unsignedDecimal('discount');
            $table->string('img_url');
            $table->foreignId('category_id');
            
            $table->foreign('category_id')->references('category_id')->on('categories');
        });
        DB::statement('ALTER TABLE products ADD CONSTRAINT products_discount_check CHECK (discount <= 100)');
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
