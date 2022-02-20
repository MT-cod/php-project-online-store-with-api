<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('goods_warehouses', static function (Blueprint $table) {
            $table->index(['goods_id', 'warehouse_id']);
            $table->bigInteger('goods_id')->unsigned();
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->bigInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->unsignedDecimal('quantity', $total = 20, $places = 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_warehouses');
    }
}
