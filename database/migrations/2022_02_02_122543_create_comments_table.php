<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('national_id');
            $table->foreignId('product_id');
            $table->boolean('has_bought');
            $table->unsignedTinyInteger('score');
            $table->string('text');
            $table->timestamp('date');
            $table->enum('status', ['pending', 'accepted', 'rejected']);

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('national_id')->references('national_id')->on('customers');
            $table->unique(['product_id', 'national_id']);

        });
        DB::statement("ALTER TABLE comments ADD CONSTRAINT comment_score_check CHECK (score <= 5)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
