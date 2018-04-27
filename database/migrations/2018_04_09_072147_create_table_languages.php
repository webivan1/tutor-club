<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();

        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value', 10)->unique();
            $table->string('name', 20);
            $table->string('script', 20)->default('Latn');
            $table->string('native', 20);
            $table->string('regional', 10);
            $table->timestamps();
        });

        // Default languages
        DB::table('languages')->insert([
            ['value' => 'de', 'name' => 'German', 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_DE'],
            ['value' => 'en', 'name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
            ['value' => 'ru', 'name' => 'Russian', 'script' => 'Cyrl', 'native' => 'Русский', 'regional' => 'ru_RU']
        ]);

        Schema::create('word_key', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150)->unique();
            $table->timestamps();
        });

        Schema::create('words', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id')->references('id')->on('languages')->onDelete('CASCADE');
            $table->integer('word_key_id')->references('id')->on('word_key')->onDelete('CASCADE');
            $table->text('translate');
            $table->timestamps();

            $table->index('language_id', 'language_id');
            $table->index('word_key_id', 'word_key_id');
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('languages');
        Schema::drop('word_key');
        Schema::drop('words');
    }
}
