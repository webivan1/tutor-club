<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnWords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('words');
        Schema::create('words', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lang', 5)->index();
            $table->integer('word_key_id')->references('id')->on('word_key')->onDelete('CASCADE');
            $table->text('translate');
            $table->timestamps();

            $table->unique(['lang', 'word_key_id'], 'lang_word_key_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('words');
        Schema::create('words', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id')->references('id')->on('languages')->onDelete('CASCADE');
            $table->integer('word_key_id')->references('id')->on('word_key')->onDelete('CASCADE');
            $table->text('translate');
            $table->timestamps();

            $table->index('language_id', 'language_id');
            $table->index('word_key_id', 'word_key_id');
        });
    }
}
