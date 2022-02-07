<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banking_transactions', function (Blueprint $table) {
            $table->foreignId('transaction_id');
            $table->string('bank_name');
            $table->string('shaparak_code');

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
        Schema::dropIfExists('banking_transactions');
    }
}
