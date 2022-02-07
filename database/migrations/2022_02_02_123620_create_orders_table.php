<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('cart_id');
            $table->enum('status', ['pending', 'finished']);
            $table->timestamp('date');
            $table->enum('payment_method', ['bank', 'in_place']);
            $table->foreignId('transaction_id')->nullable();
            $table->string('postman_national_id');

            $table->foreignId('address_id');

            $table->foreign('address_id')->references('id')->on('addresses');


            $table->foreign('cart_id')->references('cart_id')->on('carts');
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions');
            $table->foreign('postman_national_id')->references('national_id')->on('postmen');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
