<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', static function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('parent_id')->nullable()->default(0);
            $table->string('name', 100)->unique();
            $table->string('description', 200)->nullable();
            $table->unsignedInteger('level')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
