<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->index(['goods_id', 'user_id']);
            $table->integer('goods_id');
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedTinyInteger('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baskets');
    }
}
