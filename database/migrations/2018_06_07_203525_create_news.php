<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('heading', 200)->nullable();
            $table->string('slug', 150)->unique();
            $table->integer('category_id')->index();
            $table->text('content')->nullable();
            $table->integer('file_id')->index();
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->string('lang', 5)->index();
            $table->string('status', 16)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
