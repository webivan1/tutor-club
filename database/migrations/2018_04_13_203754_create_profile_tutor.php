<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTutor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutor_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('status', 20)->index();
            $table->string('country_code', 10);
            $table->string('phone', 50);
            $table->string('gender', 10);
            $table->integer('file_id')->index();
            $table->boolean('phone_verified')->default(false);
            $table->string('phone_token', 10)->nullable();
            $table->timestamp('phone_token_expire')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename', 100);
            $table->string('file_path');
            $table->string('source', 50)->nullable();
            $table->integer('source_id')->nullable();
            $table->timestamps();
            $table->index(['source', 'source_id'], 'source_source_idx');
        });

        Schema::create('content_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->index();
            $table->string('lang', 5)->index();
            $table->string('description', 255)->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('tutor_profile');
        Schema::dropIfExists('files');
        Schema::dropIfExists('content_profile');
    }
}
