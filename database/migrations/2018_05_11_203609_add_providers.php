<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->references('id')->on('users')->onDelete('CASCADE');
            $table->string('email', 200)->nullable();
            $table->string('key_code', 150)->nullable();
            $table->boolean('verify_email')->default(false);
            $table->string('source', 50);
            $table->string('source_id', 100);
            $table->text('provider_data')->nullable();
            $table->timestamps();
            $table->unique(['source', 'source_id'], 'source_source_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_providers');
    }
}
