<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostmenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postmen', function (Blueprint $table) {
            $table->string('national_id');
            $table->unsignedBigInteger('salary');

            $table->foreign('national_id')->references('national_id')->on('people');
            $table->primary('national_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postmen');
    }
}
