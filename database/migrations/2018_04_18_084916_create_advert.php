<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->integer('profile_id')->index();
            $table->string('title', 200)->nullable();
            $table->text('description');
            $table->string('lang', 5)->index();
            $table->string('status', 20)->index();
            $table->string('presentation')->nullable();
            $table->boolean('test')->default(false)->index();
            $table->integer('experience')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('advert_gallery', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advert_id')->references('id')->on('adverts')->onDelete('CASCADE');
            $table->integer('file_id')->references('id')->on('files')->onDelete('CASCADE');
            $table->unique(['advert_id', 'file_id']);
        });

        Schema::create('advert_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advert_id')->references('id')->on('adverts')->onDelete('CASCADE');
            $table->integer('category_id')->references('id')->on('category');
            $table->string('hint', 150)->nullable();
            $table->float('price_from')->index();
            $table->float('price_to')->nullable();
            $table->string('price_type')->index();
            $table->integer('minutes')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
        Schema::dropIfExists('advert_gallery');
        Schema::dropIfExists('advert_prices');
    }
}
