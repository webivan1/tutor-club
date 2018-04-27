<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableAttributeValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_id')->references('id')->on('attributes')->onDelete('CASCADE');
            $table->integer('advert_id')->references('id')->on('adverts')->onDelete('CASCADE');
            $table->string('value', 50)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_attribute');
    }
}
