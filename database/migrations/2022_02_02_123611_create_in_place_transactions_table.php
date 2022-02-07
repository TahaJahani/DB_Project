<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInPlaceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_place_transactions', function (Blueprint $table) {
            $table->foreignId('transaction_id');
            $table->enum('method', ['cash', 'cheque', 'card']);

            $table->foreign('transaction_id')->references('transaction_id')->on('transactions');
            $table->primary('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('in_place_transactions');
    }
}
