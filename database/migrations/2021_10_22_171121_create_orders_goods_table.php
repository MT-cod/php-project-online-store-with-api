<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_goods', static function (Blueprint $table) {
            $table->index(['order_id', 'goods_id']);
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->bigInteger('goods_id')->unsigned();
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->decimal('price', 10, 2)->unsigned();
            $table->unsignedDecimal('quantity', $total = 20, $places = 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_goods');
    }
}
