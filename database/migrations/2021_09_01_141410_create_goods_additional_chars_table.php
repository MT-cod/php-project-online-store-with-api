<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsAdditionalCharsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_additional_chars', function (Blueprint $table) {
            $table->index(['goods_id', 'additional_char_id']);
            $table->integer('goods_id');
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->integer('additional_char_id');
            $table->foreign('additional_char_id')->references('id')->on('additional_chars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_additional_chars');
    }
}
