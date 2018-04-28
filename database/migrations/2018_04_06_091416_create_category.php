<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150)->index();
            $table->string('slug', 150)->index();
            $table->integer('parent_id')->nullable()->references('id')->on('category')->onDelete('CASCADE');
            $table->timestamps();
            $table->string('title', 150);
            $table->string('description', 255);
            $table->unique(['parent_id', 'name']);
            $table->unique(['parent_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
}
