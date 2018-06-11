<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject', 150);
            $table->string('status', 16)->index();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('started_at');
            $table->integer('duration')->default(0);
            $table->boolean('video')->default(true);
            $table->boolean('audio')->default(true);
            $table->string('comment', 200)->nullable();
            $table->timestamps();
        });

        Schema::create('classroom_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('classroom_id')->references('id')->on('classroom')->onDelete('CASCADE');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->boolean('tutor')->default(false);
            $table->timestamps();
        });

        Schema::create('classroom_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('classroom_id')->references('id')->on('classroom')->onDelete('CASCADE');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->text('message');
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
        Schema::dropIfExists('classroom');
        Schema::dropIfExists('classroom_users');
        Schema::dropIfExists('classroom_messages');
    }
}
