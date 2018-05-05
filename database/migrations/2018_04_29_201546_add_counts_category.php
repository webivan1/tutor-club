<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->references('id')->on('category')->onDelete('CASCADE');
            $table->string('lang', 5)->index();
            $table->integer('total_categories')->default(0)->index();
            $table->integer('total_adverts')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_counts');
    }
}
